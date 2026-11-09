<?php

use App\Models\User;

it('registers a new user and returns a token', function () {
    $response = $this->postJson('/api/v1/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertCreated();
    $response->assertJsonStructure(['data' => ['id', 'name', 'email'], 'token']);
    $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
});

it('shows a validation error when the email is already taken', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $response = $this->postJson('/api/v1/register', [
        'name' => 'Jane Doe',
        'email' => 'taken@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors('email');
});

it('shows a validation error when passwords do not match', function () {
    $response = $this->postJson('/api/v1/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'nope',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors('password');
});
