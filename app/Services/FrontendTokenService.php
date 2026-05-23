<?php

namespace App\Services;

use App\Models\User;

class FrontendTokenService
{
    public const SESSION_KEY = 'vehiclepro_api_token';

    public function issue(User $user): string
    {
        $user->tokens()->where('name', 'frontend')->delete();

        return $user->createToken('frontend', ['*'])->plainTextToken;
    }

    public function storeInSession(User $user): string
    {
        $plainText = $this->issue($user);
        session()->put(self::SESSION_KEY, $plainText);

        return $plainText;
    }
}
