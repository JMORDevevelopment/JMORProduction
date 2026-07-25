<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{

    protected $table = 'gift_card';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'description',
    ];
}
