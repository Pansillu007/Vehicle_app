<?php

namespace App\Providers;

use App\Models\ServiceRecord;
use App\Models\User;
use App\Models\Vehicle;
use App\Policies\ServiceRecordPolicy;
use App\Policies\UserPolicy;
use App\Policies\VehiclePolicy;
use App\Http\View\Composers\ApiTokenComposer;
use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', ApiTokenComposer::class);

        Gate::policy(Vehicle::class, VehiclePolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        Gate::define('admin', fn (User $user) => $user->isAdmin());

        Event::listen(Login::class, function (Login $event) {
            ActivityLogger::logForUser($event->user, 'login', 'User signed in');
        });

        Event::listen(Logout::class, function () {
            session()->forget(\App\Services\FrontendTokenService::SESSION_KEY);
        });

        Gate::define('manage-service', function (User $user, Vehicle $vehicle, ?ServiceRecord $service = null) {
            $policy = new ServiceRecordPolicy;

            if ($service) {
                return $policy->update($user, $vehicle, $service);
            }

            return $policy->create($user, $vehicle);
        });
    }
}
