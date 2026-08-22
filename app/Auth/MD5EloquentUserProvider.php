<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class MD5EloquentUserProvider extends EloquentUserProvider
{
    /**
     * Validate credentials against MD5 (CI legacy) and bcrypt.
     * Re-hashes to bcrypt on successful MD5 login.
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $plain = $credentials['password'] ?? '';

        // Try bcrypt first (standard Laravel)
        if (password_verify($plain, $user->getAuthPassword())) {
            return true;
        }

        // Support MD5 passwords from CI project
        if (md5($plain) === $user->getAuthPassword()) {
            // Re-hash to bcrypt for security
            $user->forceFill(['password' => bcrypt($plain)])->save();

            return true;
        }

        return false;
    }
}
