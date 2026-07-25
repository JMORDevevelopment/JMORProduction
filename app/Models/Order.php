<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
<<<<<<< HEAD

    protected $table = 'orders';

    public $timestamps = false;

=======
    protected $table = 'orders';

    // CI's `orders` table has no `updated_at` semantics beyond what Laravel's
    // timestamps() migration already added; created_at/updated_at are kept.
>>>>>>> c33ce3f (dashboard and orders)
    protected $fillable = [
        'user_id',
        'sub_total',
        'discount',
        'grand_total',
        'create_date',
        'status',
        'checkout_data',
    ];

<<<<<<< HEAD
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function orderDetails()
=======
    protected $casts = [
        'create_date' => 'date',
        'status' => 'integer',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'order_id');
    }

    public function details()
>>>>>>> c33ce3f (dashboard and orders)
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}
