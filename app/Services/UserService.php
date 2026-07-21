<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected $user_id;
    protected $firstname;
    protected $lastname;
    protected $image;
    protected $loged = false;

    public function __construct()
    {
        $user_id = Session::get('user_id');
        if (!$user_id && isset($_COOKIE['user_id'])) {
            Session::put('user_id', $_COOKIE['user_id']);
            $user_id = $_COOKIE['user_id'];
        }
        if ($user_id) {
            $this->generate($user_id);
        }
    }

    public function login($user)
    {
        $this->user_id = $user['user_id'];
        $this->loged = true;
        Session::put('user_id', $user['user_id']);
        Session::put('token', bin2hex(random_bytes(25)));
        $this->firstname = $user['firstname'];
        $this->lastname = $user['lastname'];
        $this->image = $user['image'] ?? null;
    }

    public function generate($user_id)
    {
        $user = DB::table('user')->where('user_id', $user_id)->first();
        if (!$user) {
            Session::forget('user_id');
            return;
        }
        $this->user_id = $user_id;
        $this->loged = true;
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->image = $user->image ?? null;
    }

    // Getters
    public function getFirstName()   { return $this->firstname; }
    public function getLastName()    { return $this->lastname; }
    public function getImage()       { return $this->image; }
    public function getUserId()      { return $this->user_id; }
    public function isLogged()       { return $this->loged; }
}