<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{

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
