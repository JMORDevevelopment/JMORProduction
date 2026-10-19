<?php

use App\Models\Admin;
use Illuminate\Database\QueryException;

test('the admin table rejects duplicate email addresses at the database level', function () {
    Admin::create([
        'firstname' => 'First',
        'lastname' => 'Admin',
        'email' => 'dupe@example.com',
        'password' => bcrypt('secret-password'),
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);

    Admin::create([
        'firstname' => 'Second',
        'lastname' => 'Admin',
        'email' => 'dupe@example.com',
        'password' => bcrypt('secret-password'),
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);
})->throws(QueryException::class);
