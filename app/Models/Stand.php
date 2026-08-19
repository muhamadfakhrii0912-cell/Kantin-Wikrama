<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stand extends Model
{
    protected $fillable = ['canteen_id', 'name', 'description', 'image', 'sort_order'];

    public function canteen()
    {
        return $this->belongsTo(Canteen::class);
    }

    public function admins()
    {
        return $this->hasMany(User::class, 'stand_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function orderStands()
    {
        return $this->hasMany(OrderStand::class);
    }
}
