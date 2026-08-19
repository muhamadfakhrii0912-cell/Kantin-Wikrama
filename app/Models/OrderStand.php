<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStand extends Model
{
    protected $fillable = [
        'order_id', 'stand_id', 'status', 'ready_time'
    ];

    protected function casts(): array
    {
        return [
            'ready_time' => 'datetime',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function stand()
    {
        return $this->belongsTo(Stand::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
