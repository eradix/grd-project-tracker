<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginAction
{
    public function execute(array $credentials): array
    {
        if (! Auth::validate($credentials)) {
            abort(401, 'Invalid credentials.');
        }

        $user = User::where('email', $credentials['email'])->firstOrFail();

        return [
            'user' => $user,
            'token' => $user->createToken('api-token')->plainTextToken,
        ];
    }
}
