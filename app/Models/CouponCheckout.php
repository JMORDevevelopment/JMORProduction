<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponCheckout extends Model
{
  
    protected $table = 'coupon_checkout';

    public $timestamps = false;

    protected $fillable = [
        'gift_card_id',
        'order_id',
        'coupon_number',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function giftCard()
    {
        return $this->belongsTo(GiftCard::class, 'gift_card_id');
    }
}
