<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    // CI's `orders` table has no `updated_at` semantics beyond what Laravel's
    // timestamps() migration already added; created_at/updated_at are kept.
    protected $fillable = [
        'user_id',
        'sub_total',
        'discount',
        'grand_total',
        'create_date',
        'status',
        'checkout_data',
    ];

    protected $casts = [
        'create_date' => 'date',
        'status' => 'integer',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'order_id');
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}
