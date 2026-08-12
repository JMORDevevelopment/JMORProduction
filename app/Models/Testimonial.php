<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimony_form';

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'service_used',
        'message',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
        'published' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'user_id');
    }
}
