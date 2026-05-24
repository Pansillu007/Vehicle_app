<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\FrontendTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class SessionBootstrapController extends Controller
{
    public function __construct(
        protected FrontendTokenService $tokens
    ) {}

    /**
     * Bridge a Sanctum Bearer token into a web session so Blade pages remain accessible.
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $accessToken = PersonalAccessToken::findToken($request->token);

        if (! $accessToken || ! $this->tokens->isAppToken($accessToken->name)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token.',
            ], 401);
        }

        $user = $accessToken->tokenable;

        $guard = Auth::guard('web');

        if ($guard->check() && $guard->id() !== $user->id) {
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Store token before login so Login listeners do not rotate it.
        $request->session()->put(FrontendTokenService::SESSION_KEY, $request->token);

        if (! $guard->check()) {
            $guard->login($user, remember: true);
            $request->session()->regenerate();
        }

        $request->session()->save();

        return response()->json([
            'success' => true,
            'message' => 'Session established.',
            'data' => [
                'token' => $request->token,
                'redirect' => route('dashboard'),
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ],
            ],
        ]);
    }
}
