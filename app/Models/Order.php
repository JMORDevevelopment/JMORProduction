<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Matches database/migrations/..._create_orders_table.php: default `id`
    // PK and Laravel timestamps, alongside the legacy `create_date` column.
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'sub_total',
        'discount',
        'grand_total',
        'create_date',
        'status',
        'checkout_data',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}
