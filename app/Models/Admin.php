<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

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
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Check password supporting both MD5 (CI legacy) and bcrypt.
     * Re-hashes to bcrypt on successful MD5 login.
     */
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
