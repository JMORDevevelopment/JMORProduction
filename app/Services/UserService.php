<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserService
{
    protected ?int $userId = null;

    protected ?string $firstname = null;

    protected ?string $lastname = null;

    protected ?string $image = null;

    protected bool $logged = false;

    public function __construct(Request $request)
    {
        $userId = Session::get('user_id') ?? $request->cookie('user_id');

        if ($userId) {
            Session::put('user_id', $userId);
            $this->hydrate((int) $userId);
        }
    }

    /**
     * Populate the service from an already-authenticated user.
     */
    public function login(User $user): void
    {
        $this->userId = $user->user_id;
        $this->logged = true;
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->image = $user->image;

        Session::put('user_id', $user->user_id);
        Session::put('token', Str::random(50));
    }

    /**
     * Load a user by id and populate the service from it. Clears the
     * session if the id no longer matches a real user.
     */
    public function hydrate(int $userId): void
    {
        $user = User::find($userId);

        if (! $user) {
            Session::forget('user_id');

            return;
        }

        $this->userId = $user->user_id;
        $this->logged = true;
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->image = $user->image;
    }

    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    public function getLastName(): ?string
    {
        return $this->lastname;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function isLogged(): bool
    {
        return $this->logged;
    }
}
