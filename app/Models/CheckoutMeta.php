<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutMeta extends Model
{
    protected $table = 'checkout_meta';

    public $timestamps = false;

    protected $fillable = [
        'form_name',
        'package_id',
    ];

    public function formFields()
    {
        return $this->hasMany(CheckoutForm::class, 'form_id');
    }

    public function systemFields()
    {
        return $this->hasMany(SystemInformation::class, 'form_id');
    }
}
