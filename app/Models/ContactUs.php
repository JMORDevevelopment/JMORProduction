<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    // NOTE: schema from ContactController's insert (name, email, phone,
    // reason, message, ip, date_time). No created_at/updated_at columns;
    // the legacy table tracks its own `date_time` instead.
    protected $table = 'contact_us';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'reason',
        'message',
        'ip',
        'date_time',
    ];
}
