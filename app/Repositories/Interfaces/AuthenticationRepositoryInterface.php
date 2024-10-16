<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface AuthenticationRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function createToken(User $user, string $device_name): string;
}
