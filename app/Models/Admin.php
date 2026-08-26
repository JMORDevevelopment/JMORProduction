<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticatable;

class Admin extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use TwoFactorAuthenticatable;

    protected $table = 'admin';

    protected $primaryKey = 'admin_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'firstname',
        'lastname',
        'image',
        'last_login',
        'date_register',
        'password',
        'email',
        'status',
        'role',
        'auth_mode',
    ];

    protected $hidden = [
        'password',
    ];

    public function getNameAttribute(): string
    {
        return trim($this->firstname.' '.$this->lastname);
    }

    /**
     * Check password supporting both MD5 (CI legacy) and bcrypt.
     * Re-hashes to bcrypt on successful MD5 login.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * The CI admin table has no remember_token column.
     */
    public function getRememberTokenName(): string
    {
        return '';
    }

    public function validatePassword(string $password): bool
    {
        if (password_verify($password, $this->password)) {
            return true;
        }

        // Support MD5 passwords from CI project
        if (md5($password) === $this->password) {
            // Re-hash to bcrypt for security
            $this->forceFill(['password' => bcrypt($password)])->save();

            return true;
        }

        return false;
    }
}
