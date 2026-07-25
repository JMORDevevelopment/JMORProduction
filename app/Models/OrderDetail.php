<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details';

    // No created_at/updated_at columns in CI's schema; only date_added.
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

    protected $casts = [
        'qty' => 'integer',
        'price' => 'float',
        'sub_total' => 'float',
        'date_added' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
