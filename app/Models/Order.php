<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // The shipped migration (..._create_orders_table.php) added Laravel
    // timestamps, but the LIVE `orders` table only has `id`, `user_id`,
    // `sub_total`, `discount`, `grand_total`, `create_date`, `status`, and
    // `checkout_data` — no created_at/updated_at. Disabled to match.
    protected $table = 'orders';

    public $timestamps = false;

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
