<?php

namespace App\Livewire;

use App\Models\OrderStand;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AdminOrderList extends Component
{
    public $searchPin = '';
    public $statusFilter = 'all';

    public function updateStatus($orderStandId, $newStatus)
    {
        $orderStand = OrderStand::find($orderStandId);

        // Pastikan order_stand milik stan admin yang sedang login
        if ($orderStand && $orderStand->stand_id == Auth::user()->stand_id) {
            $orderStand->status = $newStatus;
            $orderStand->save();

            // Cek jika semua sub-order stan sudah selesai/picked_up, update status induk (orders)
            $parentOrder = $orderStand->order;
            $allStatuses = $parentOrder->orderStands()->pluck('status')->toArray();
            
            if (collect($allStatuses)->every(fn($s) => $s === 'picked_up')) {
                $parentOrder->status = 'picked_up';
            } elseif (collect($allStatuses)->contains('in_progress') || collect($allStatuses)->contains('ready_for_pickup')) {
                $parentOrder->status = 'in_progress';
            }
            $parentOrder->save();

            session()->flash('success', "Status pesanan berhasil diperbarui menjadi {$newStatus}.");
        }
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = OrderStand::where('stand_id', $user->stand_id)
            ->with(['order.user', 'orderItems.menu'])
            ->latest();

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if (!empty($this->searchPin)) {
            $query->whereHas('order', function($q) {
                $q->where('pin', 'like', '%' . $this->searchPin . '%');
            });
        }

        $orderStands = $query->get();

        return view('livewire.admin-order-list', [
            'orderStands' => $orderStands
        ]);
    }
}
