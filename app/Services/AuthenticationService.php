<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\AuthenticationRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class AuthenticationService
{
    protected $authenticationRepository;

    public function __construct(AuthenticationRepositoryInterface $authenticationRepository)
    {
        $this->authenticationRepository = $authenticationRepository;
    }

    public function login(string $email, string $password, string $device_name): User
    {
        $user = $this->authenticationRepository->findByEmail($email);

        if ( ! $user ) {
            throw ValidationException::withMessages([
                'email' => ['The provided credntials are incorrect.'],
            ]);
        }

        if ( ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided credntials are incorrect.'],
            ]);
        }


        $token = $this->authenticationRepository->createToken($user, $device_name ?? 'Android');
        $user->plain_text = $token;

        return $user;
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
