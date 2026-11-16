<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $table = 'contact_us';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'reason',
        'message',
        'assessment_scope',
        'business_name',
        'business_type',
        'resume_path',
        'status',
        'ip',
        'date_time',
    ];
}
