<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->user = User::factory()->create([
        'email' => 'king_james@example.com',
        'password' => Hash::make('password123'),
    ]);
});

it('logs in with correct credentials and returns a token', function () {
    $response = $this->postJson('/api/v1/login', [
        'email' => 'king_james@example.com',
        'password' => 'password123',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['data' => ['id', 'name', 'email'], 'token']);
});

it('rejects an incorrect password with a generic error', function () {
    $response = $this->postJson('/api/v1/login', [
        'email' => 'king_james@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertUnauthorized();
});

it('rejects a non-existent email with the same generic error', function () {
    $response = $this->postJson('/api/v1/login', [
        'email' => 'nobody@example.com',
        'password' => 'password123',
    ]);

    $response->assertUnauthorized();
});
