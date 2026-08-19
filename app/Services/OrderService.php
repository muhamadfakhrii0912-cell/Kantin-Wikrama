<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderStand;
use App\Models\OrderItem;
use App\Models\MenuStock;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * @param User $user
     * @param array $cartItems format: [['menu_id' => 1, 'quantity' => 2, 'price' => 12000], ...]
     * @param string $pickupTime 'Y-m-d H:i:s'
     * @param string|null $note
     * @return Order
     * @throws Exception
     */
    public function placeOrder(User $user, array $cartItems, string $pickupTime, ?string $note = null): Order
    {
        return DB::transaction(function () use ($user, $cartItems, $pickupTime, $note) {
            $stockDate = date('Y-m-d', strtotime($pickupTime));
            
            // 1. Group items by stand_id to easily create sub-orders and calculate total
            $itemsByStand = [];
            $totalPrice = 0;
            
            // Fetch all menus in the cart
            $menuIds = array_column($cartItems, 'menu_id');
            $menus = Menu::whereIn('id', $menuIds)->get()->keyBy('id');

            foreach ($cartItems as $item) {
                if (!isset($menus[$item['menu_id']])) {
                    throw new Exception("Menu ID {$item['menu_id']} tidak ditemukan.");
                }
                $menu = $menus[$item['menu_id']];
                $standId = $menu->stand_id;
                
                if (!isset($itemsByStand[$standId])) {
                    $itemsByStand[$standId] = [];
                }
                
                $itemsByStand[$standId][] = [
                    'menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                ];
                
                $totalPrice += $menu->price * $item['quantity'];
            }

            // 2. Validate and reserve stocks with lockForUpdate to prevent race conditions
            foreach ($cartItems as $item) {
                $menu = $menus[$item['menu_id']];
                
                // if daily_quota is > 0, we track stock
                if ($menu->daily_quota > 0) {
                    $stock = MenuStock::where('menu_id', $menu->id)
                                      ->where('stock_date', $stockDate)
                                      ->lockForUpdate()
                                      ->first();
                                      
                    if (!$stock) {
                        // If stock record doesn't exist, create it with max quota
                        $stock = MenuStock::create([
                            'menu_id' => $menu->id,
                            'stock_date' => $stockDate,
                            'remaining_qty' => $menu->daily_quota,
                        ]);
                    }

                    if ($stock->remaining_qty < $item['quantity']) {
                        throw new Exception("Stok untuk menu {$menu->name} tidak mencukupi. Sisa: {$stock->remaining_qty}");
                    }

                    // Cut stock
                    $stock->remaining_qty -= $item['quantity'];
                    $stock->save();
                }
            }

            // 3. Generate PIN & Order Number
            $pin = $this->generatePin();
            $orderNumber = $this->createOrderNumber();

            // 4. Create Master Order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'total_price' => $totalPrice,
                'pickup_time' => $pickupTime,
                'note' => $note,
                'pin' => $pin,
                'status' => 'pending',
            ]);

            // 5. Create OrderStands and OrderItems
            foreach ($itemsByStand as $standId => $items) {
                $orderStand = OrderStand::create([
                    'order_id' => $order->id,
                    'stand_id' => $standId,
                    'status' => 'pending',
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_stand_id' => $orderStand->id,
                        'menu_id' => $item['menu_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }
            }

            // Optional: Trigger event for real-time notification
            // event(new OrderPlaced($order));

            return $order;
        });
    }

    private function generatePin(): string
    {
        return str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function createOrderNumber(): string
    {
        $prefix = 'ORD-' . date('ymd') . '-';
        
        $latestOrder = Order::where('order_number', 'LIKE', $prefix . '%')
                            ->orderBy('id', 'desc')
                            ->first();

        if ($latestOrder) {
            $lastNumber = intval(substr($latestOrder->order_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad((string) $newNumber, 4, '0', STR_PAD_LEFT);
    }
}
