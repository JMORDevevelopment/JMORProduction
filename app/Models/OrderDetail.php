<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    // NOTE: schema from CheckoutService/PaymentService usage (item, type,
    // qty, price, sub_total, order_id, date_added). No migration ships this
    // table yet; confirm real columns/PK with `DESCRIBE order_details;`.
    protected $table = 'order_details';

    public $timestamps = false;

    protected $fillable = [
        'item',
        'type',
        'qty',
        'price',
        'sub_total',
        'order_id',
        'date_added',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
