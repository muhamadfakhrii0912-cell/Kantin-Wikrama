<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'total_price', 
        'pickup_time', 'note', 'pin', 'status'
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
            'pickup_time' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderStands()
    {
        return $this->hasMany(OrderStand::class);
    }
}
