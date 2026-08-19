<?php

namespace App\Livewire;

use App\Models\Menu;
use App\Services\OrderService;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Exception;

class CartComponent extends Component
{
    public $cart = []; // Array of ['menu' => Menu, 'quantity' => int]
    public $totalPrice = 0;
    
    public $pickupTime;
    public $note;
    
    public $errorMessage = null;

    public function mount()
    {
        // Set default pickup time to 15 minutes from now
        $this->pickupTime = now()->addMinutes(15)->format('Y-m-d\TH:i');
    }

    #[On('menu-added-to-cart')]
    public function addMenu($menuId)
    {
        $this->errorMessage = null;
        
        if (isset($this->cart[$menuId])) {
            $this->cart[$menuId]['quantity']++;
        } else {
            $menu = Menu::find($menuId);
            if ($menu) {
                $this->cart[$menuId] = [
                    'menu' => $menu,
                    'quantity' => 1
                ];
            }
        }
        
        $this->calculateTotal();
    }

    public function increaseQuantity($menuId)
    {
        $this->errorMessage = null;
        if (isset($this->cart[$menuId])) {
            $this->cart[$menuId]['quantity']++;
            $this->calculateTotal();
        }
    }

    public function decreaseQuantity($menuId)
    {
        $this->errorMessage = null;
        if (isset($this->cart[$menuId])) {
            if ($this->cart[$menuId]['quantity'] > 1) {
                $this->cart[$menuId]['quantity']--;
            } else {
                unset($this->cart[$menuId]);
            }
            $this->calculateTotal();
        }
    }

    public function removeItem($menuId)
    {
        $this->errorMessage = null;
        unset($this->cart[$menuId]);
        $this->calculateTotal();
    }

    private function calculateTotal()
    {
        $this->totalPrice = 0;
        foreach ($this->cart as $item) {
            $this->totalPrice += $item['menu']->price * $item['quantity'];
        }
    }

    public function checkout(OrderService $orderService)
    {
        $this->errorMessage = null;

        if (empty($this->cart)) {
            $this->errorMessage = "Keranjang masih kosong.";
            return;
        }

        if (!$this->pickupTime) {
            $this->errorMessage = "Waktu pengambilan harus diisi.";
            return;
        }

        try {
            // Format data keranjang untuk OrderService
            $cartData = [];
            foreach ($this->cart as $menuId => $item) {
                $cartData[] = [
                    'menu_id' => $menuId,
                    'quantity' => $item['quantity'],
                    'price' => $item['menu']->price, // Snapshot harga
                ];
            }

            // Ganti format datetime HTML5 ('Y-m-d\TH:i') ke MySQL ('Y-m-d H:i:s')
            $formattedPickupTime = str_replace('T', ' ', $this->pickupTime) . ':00';

            // Eksekusi pesanan melalui Service (Transactional)
            $order = $orderService->placeOrder(
                Auth::user(),
                $cartData,
                $formattedPickupTime,
                $this->note
            );

            // Bersihkan keranjang
            $this->cart = [];
            $this->totalPrice = 0;

            // Redirect ke halaman sukses (nanti kita buat route-nya)
            // return redirect()->route('orders.show', $order->id);
            // Untuk sementara tampilkan sukses saja:
            session()->flash('success', "Pesanan berhasil dibuat! PIN Anda: {$order->pin}");
            
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.cart-component');
    }
}
