<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
      'order_id',
      'menu_id',
      'count',
      'price',
    ];

    protected $hidden = [
        'id',
        'order_id',
        'menu_id',
        'count',
        'price',
        'created_at',
        'updated_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }
}
