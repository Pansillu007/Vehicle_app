<?php

namespace App\Providers;

<<<<<<< HEAD
=======
use App\Models\ServiceRecord;
use App\Models\User;
use App\Models\Vehicle;
use App\Policies\ServiceRecordPolicy;
use App\Policies\UserPolicy;
use App\Policies\VehiclePolicy;
use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
>>>>>>> ec6237d (Third Week of Assignment small changes)
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
<<<<<<< HEAD
    /**
     * Register any application services.
     */
=======
    protected $policies = [
        Vehicle::class => VehiclePolicy::class,
        User::class => UserPolicy::class,
    ];

>>>>>>> ec6237d (Third Week of Assignment small changes)
    public function register(): void
    {
        //
    }

<<<<<<< HEAD
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
=======
    public function boot(): void
    {
        Gate::policy(Vehicle::class, VehiclePolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        Gate::define('admin', fn (User $user) => $user->isAdmin());

        Event::listen(Login::class, function (Login $event) {
            ActivityLogger::logForUser($event->user, 'login', 'User signed in');
        });

        Gate::define('manage-service', function (User $user, Vehicle $vehicle, ?ServiceRecord $service = null) {
            $policy = new ServiceRecordPolicy;

            if ($service) {
                return $policy->update($user, $vehicle, $service);
            }

            return $policy->create($user, $vehicle);
        });
>>>>>>> ec6237d (Third Week of Assignment small changes)
    }
}
