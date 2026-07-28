<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutForm extends Model
{
    protected $table = 'checkout_form';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'placeholder',
        'required',
        'label',
        'types',
        'form_id',
    ];

    public function meta()
    {
        return $this->belongsTo(CheckoutMeta::class, 'form_id');
    }
}
