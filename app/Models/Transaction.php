<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';

    // CI's `transaction` table has no created_at/updated_at columns —
    // it only has `published`, which is timestamp-on-update/current.
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'order_type',
        'checkout_type',
        'transaction_id',
        'auth_code',
        'user_id',
        'amount',
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
