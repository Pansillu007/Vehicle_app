<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GoogleAuthService
{
    public function resolveUser(SocialiteUser $googleUser): User
    {
        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();

        if (! $googleId || ! $email) {
            throw new HttpException(422, 'Google did not return a valid account email.');
        }

        $byGoogleId = User::where('google_id', $googleId)->first();
        if ($byGoogleId) {
            return $this->syncProfile($byGoogleId, $googleUser);
        }

        $byEmail = User::where('email', $email)->first();
        if ($byEmail) {
            if ($byEmail->google_id && $byEmail->google_id !== $googleId) {
                throw new HttpException(409, 'This email is linked to a different Google account.');
            }

            return $this->linkGoogleAccount($byEmail, $googleUser);
        }

        return $this->createUser($googleUser);
    }

    protected function createUser(SocialiteUser $googleUser): User
    {
        return User::create([
            'name' => $googleUser->getName() ?: Str::before($googleUser->getEmail(), '@'),
            'email' => $googleUser->getEmail(),
            'password' => Hash::make(Str::random(64)),
            'role' => UserRole::User,
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
            'provider' => 'google',
            'email_verified_at' => now(),
        ]);
    }

    protected function linkGoogleAccount(User $user, SocialiteUser $googleUser): User
    {
        $user->update([
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar() ?: $user->avatar,
            'provider' => 'google',
            'email_verified_at' => $user->email_verified_at ?? now(),
        ]);

        return $this->syncProfile($user->fresh(), $googleUser);
    }

    protected function syncProfile(User $user, SocialiteUser $googleUser): User
    {
        $user->update([
            'name' => $googleUser->getName() ?: $user->name,
            'avatar' => $googleUser->getAvatar() ?: $user->avatar,
            'provider' => 'google',
        ]);

        return $user->fresh();
    }
}
