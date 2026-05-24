<?php

namespace App\Services;

use App\Models\User;

class FrontendTokenService
{
    public const SESSION_KEY = 'vehiclepro_api_token';

    /** Sanctum personal access token name for the SPA / Axios client. */
    public const TOKEN_NAME = 'vehiclepro-token';

    public function issue(User $user): string
    {
        $user->tokens()->where('name', self::TOKEN_NAME)->delete();

        return $user->createToken(self::TOKEN_NAME, ['*'])->plainTextToken;
    }

    public function isAppToken(?string $tokenName): bool
    {
        return $tokenName === self::TOKEN_NAME || $tokenName === 'frontend';
    }

    public function storeInSession(User $user): string
    {
        $plainText = $this->issue($user);
        session()->put(self::SESSION_KEY, $plainText);

        return $plainText;
    }
}
