<?php

namespace App\Actions\Fortify;

<<<<<<< HEAD
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
=======
use App\Enums\UserRole;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
>>>>>>> ec6237d (Third Week of Assignment small changes)

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

<<<<<<< HEAD
=======
    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
>>>>>>> ec6237d (Third Week of Assignment small changes)
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
<<<<<<< HEAD
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => 'user',
        ]);
=======
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => UserRole::User,
        ]);

        $user->notify(new WelcomeNotification);
        ActivityLogger::logForUser($user, 'registered', 'Account created successfully');

        return $user;
>>>>>>> ec6237d (Third Week of Assignment small changes)
    }
}
