<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use App\Services\FrontendTokenService;
use App\Services\GoogleAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class GoogleAuthController extends Controller
{
    public function __construct(
        protected GoogleAuthService $googleAuth,
        protected FrontendTokenService $tokens
    ) {}

    public function redirect(): RedirectResponse
    {
        if (! $this->googleConfigured()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google sign-in is not configured.']);
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        if (! $this->googleConfigured()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google sign-in is not configured.']);
        }

        try {
            $googleUser = Socialite::driver('google')->user();
            $user = $this->googleAuth->resolveUser($googleUser);
        } catch (HttpException $e) {
            return redirect()->route('login')->withErrors(['email' => $e->getMessage()]);
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('login')
                ->withErrors(['email' => 'Google sign-in failed. Please try again or use email/password.']);
        }

        Auth::login($user, remember: true);
        request()->session()->regenerate();

        $this->tokens->storeInSession($user);

        ActivityLogger::logForUser($user, 'login', 'Signed in with Google');

        return redirect()->intended(route('dashboard'));
    }

    protected function googleConfigured(): bool
    {
        return filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'))
            && filled(config('services.google.redirect'));
    }
}
