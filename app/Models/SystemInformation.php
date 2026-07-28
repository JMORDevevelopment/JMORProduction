<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemInformation extends Model
{
    protected $table = 'system_information';

    public $timestamps = false;

    protected $fillable = [
        's_name',
        's_placeholder',
        's_required',
        's_label',
        's_types',
        'form_id',
    ];

    public function meta()
    {
        return $this->belongsTo(CheckoutMeta::class, 'form_id');
    }
}
