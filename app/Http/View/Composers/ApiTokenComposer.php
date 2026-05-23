<?php

namespace App\Http\View\Composers;

use App\Services\FrontendTokenService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ApiTokenComposer
{
    public function __construct(
        protected FrontendTokenService $tokens
    ) {}

    /**
     * Issue a Sanctum token for the authenticated session (SPA-lite frontend).
     */
    public function compose(View $view): void
    {
        if (! Auth::check()) {
            return;
        }

        if (! session()->has(FrontendTokenService::SESSION_KEY)) {
            $this->tokens->storeInSession(Auth::user());
        }

        $view->with('apiToken', session(FrontendTokenService::SESSION_KEY));
    }
}
