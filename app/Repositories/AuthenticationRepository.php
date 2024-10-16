<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\AuthenticationRepositoryInterface;

class AuthenticationRepository implements AuthenticationRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function createToken(User $user, string $device_name): string
    {
        return $user->createToken($device_name)->plainTextToken;
    }
}
