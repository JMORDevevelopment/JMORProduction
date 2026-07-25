<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // NOTE: schema from PaymentService's insert (order_id, order_type,
    // checkout_type, transaction_id, user_id, amount, auth_code). The
    // legacy table name is singular; confirm real columns/PK with
    // `DESCRIBE transaction;`.
    protected $table = 'transaction';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'order_type',
        'checkout_type',
        'transaction_id',
        'user_id',
        'amount',
        'auth_code',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
