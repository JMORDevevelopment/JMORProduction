<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GiftCard extends Model
{
    protected $table = 'gift_card';

    public $timestamps = false;

    protected $fillable = [
        'link',
        'name',
        'heading',
        'description',
        'image',
        'price',
        'upfront',
        'category',
        'coupon_number',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function couponCheckouts(): HasMany
    {
        return $this->hasMany(CouponCheckout::class, 'gift_card_id');
    }
}
