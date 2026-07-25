<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    // NOTE: schema assumed from PaymentService/CartController usage (name,
    // price, description). Confirm real columns/PK with `DESCRIBE gift_card;`.
    protected $table = 'gift_card';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'description',
    ];
}
