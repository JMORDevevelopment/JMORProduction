<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
<<<<<<< HEAD
    // NOTE: schema from PaymentService's insert (order_id, order_type,
    // checkout_type, transaction_id, user_id, amount, auth_code). The
    // legacy table name is singular; confirm real columns/PK with
    // `DESCRIBE transaction;`.
    protected $table = 'transaction';

=======
    protected $table = 'transaction';

    // CI's `transaction` table has no created_at/updated_at columns —
    // it only has `published`, which is timestamp-on-update/current.
>>>>>>> c33ce3f (dashboard and orders)
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'order_type',
        'checkout_type',
        'transaction_id',
<<<<<<< HEAD
        'user_id',
        'amount',
        'auth_code',
    ];

=======
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
>>>>>>> c33ce3f (dashboard and orders)
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
