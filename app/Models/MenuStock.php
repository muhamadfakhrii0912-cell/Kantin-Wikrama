<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuStock extends Model
{
    protected $fillable = ['menu_id', 'stock_date', 'remaining_qty'];

    protected function casts(): array
    {
        return [
            'stock_date' => 'date',
        ];
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
