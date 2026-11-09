<?php

use App\Models\User;

it('revokes the token used for the request', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token');

    $this->withHeader('Authorization', "Bearer {$token->plainTextToken}")
        ->postJson('/api/v1/logout')
        ->assertNoContent();

    $this->assertDatabaseMissing('personal_access_tokens', ['id' => $token->accessToken->id]);
});

it('rejects an unauthenticated logout attempt', function () {
    $this->postJson('/api/v1/logout')->assertUnauthorized();
});
