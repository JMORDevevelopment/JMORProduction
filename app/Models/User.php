<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'user_group_id', 'firstname', 'lastname', 'image',
        'date_birth', 'gender', 'region_id', 'ip', 'city',
        'address', 'zip', 'state', 'company', 'email',
        'date_added', 'password', 'nation_id', 'ban'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'date_birth' => 'date',
        'date_added' => 'date',
        'ban' => 'integer',
    ];

    // Helper methods
    public function getFullNameAttribute(): string
    {
        return trim($this->firstname . ' ' . $this->lastname);
    }

    public function getAvatarAttribute(): string
    {
        return $this->image ? asset('uploads/users/' . $this->image) : asset('images/default-avatar.png');
    }
}