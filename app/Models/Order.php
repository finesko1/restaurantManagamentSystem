<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'table_id',
        'status'
    ];

    protected $hidden = [
        'id',
        'table_id',
        'status',
        'created_at',
        'updated_at',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}
