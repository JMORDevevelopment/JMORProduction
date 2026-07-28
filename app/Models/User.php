<?php

namespace App\Models;

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
        'date_added', 'password', 'nation_id', 'ban',
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
        return trim($this->firstname.' '.$this->lastname);
    }

    public function getAvatarAttribute(): string
    {
        return $this->image ? asset('uploads/users/'.$this->image) : asset('images/default-avatar.png');
    }

    /**
     * The live `user` table has no `remember_token` column, so "remember me"
     * support is disabled here. Returning an empty name tells the auth
     * guard not to read/write a remember token for this model — without
     * this override, checking "remember me" on login would try to
     * UPDATE `user` SET remember_token = ... and fail with an
     * "Unknown column" error, same as the log_history/orders timestamp bug.
     */
    public function getRememberTokenName()
    {
        return '';
    }
}
