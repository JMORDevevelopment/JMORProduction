<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestInformation extends Model
{
    protected $table = 'request_information';

    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'company',
        'email',
        'phone',
        'fax',
        'address',
        'suite',
        'city',
        'state',
        'zip',
        'service_intersted',
        'message',
        'protection_question',
        'status',
        'ip',
    ];
}
