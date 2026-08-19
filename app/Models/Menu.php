<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'stand_id', 'category_id', 'name', 'description', 
        'price', 'image', 'daily_quota', 'estimated_minutes', 'is_available'
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_available' => 'boolean',
        ];
    }

    public function stand()
    {
        return $this->belongsTo(Stand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stocks()
    {
        return $this->hasMany(MenuStock::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
