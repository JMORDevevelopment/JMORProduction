<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
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

    protected $casts = [
        'amount' => 'float',
        'published' => 'datetime',
    ];

    /**
     * Mirrors the CI view's per-row lookup:
     *   $this->db->get_where('orders', array('id' => $trans_data['order_id']))->row()->status;
     * Replaced here with an eager-loadable relationship instead of an N+1 query.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}