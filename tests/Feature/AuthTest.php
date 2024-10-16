<?php

use App\Models\User;
use Illuminate\Support\Facades\Storage;

// A successfull user authentication
it('can successfully login a user', function () {
    /** @var User $user */
    $user = $this->getUserByEmail();
    $response = $this->postJson(env('SYSTEM_API') . 'api/v1/login', [
        'email' => $user->email,
        'password' => '12345678',
    ], [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ]);

    $response->assertStatus(200)->assertJson([
        'result' => [
            "token" => true
        ]
    ]);

    Storage::disk('local')->put('responses/auth/user.login.json', json_encode($response->collect()));
});

// A wrong user password authentication
it('fails authentication with wrong password', function () {
    /** @var User $user */
    $user = $this->getUserByEmail();
    $response = $this->postJson(env('SYSTEM_API') . 'api/v1/login', [
        'email' => $user->email,
        'password' => '12345678213123',
    ], [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ]);

    $response->assertStatus(422);
    Storage::disk('local')->put('responses/auth/user.login.wrongpass.json', json_encode($response->collect()));
});

// Authentication with a non-existent email
it('fails authentication with non-existent email', function () {
    $response = $this->postJson(env('SYSTEM_API') . 'api/v1/login', [
        'email' => 'test@examplesadasd.sad',
        'password' => '12345678',
    ], [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ]);

    $response->assertStatus(422);
    Storage::disk('local')->put('responses/auth/user.login.emailnotexist.json', json_encode($response->collect()));
});

// Logout a successfully authenticated user
it('can successfully logout an authenticated user', function () {
    $this->user = $this->getUserByEmail();
    $token = $this->user->createToken($device_name ?? 'Android')->plainTextToken;

    $this->withHeader('Authorization', 'Bearer ' . $token);

    $testResponse = $this->get(env('SYSTEM_API') . 'api/v1/logout');
    $testResponse->assertNoContent();
});

// Logout with an incorrect token
it('fails logout with incorrect auth token', function () {
    $this->withHeader('Authorization', 'Bearer ' . fake()->iosMobileToken());

    $response = $this->getJson(env('SYSTEM_API') . 'api/v1/logout');
    $response->assertUnauthorized();
    Storage::disk('local')->put('responses/auth/user.login.unauthorized.json', json_encode($response->collect()));
});
