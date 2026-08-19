<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canteen extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'image', 'sort_order'];

    public function stands()
    {
        return $this->hasMany(Stand::class);
    }
}
