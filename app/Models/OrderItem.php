<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_stand_id', 'menu_id', 'quantity', 'price'
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function orderStand()
    {
        return $this->belongsTo(OrderStand::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
