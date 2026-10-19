<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticatable;

class Admin extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use HasRoles;
    use TwoFactorAuthenticatable;

    protected $table = 'admin';

    protected $primaryKey = 'admin_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    /**
     * Roles and permissions are bound to the panel's admin guard.
     */
    protected ?string $guard_name = 'admin';

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
     * Only active accounts (status = 1) may access the panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return (int) $this->status === 1;
    }

    /**
     * Super admins (role = 1) hold unrestricted access; every other
     * admin is limited to the permissions granted via Shield.
     */
    public function isAdmin(): bool
    {
        return (int) $this->role === 1;
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
