<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = [
        'name',
        'price',
        'preparation_time'
    ];

    protected $hidden = [
        'id',
        'name',
        'price',
        'preparation_time',
        'created_at',
        'updated_at',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'menu_id', 'id');
    }
}
