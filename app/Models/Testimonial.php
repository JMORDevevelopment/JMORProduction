<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimony_form';

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'display_name',
        'service_used',
        'message',
        'status',
        'consent_to_publish',
        'verified_at',
        'source_url',
        'published',
    ];

    protected $casts = [
        'status' => 'integer',
        'consent_to_publish' => 'boolean',
        'verified_at' => 'datetime',
        'published' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'user_id');
    }
}
