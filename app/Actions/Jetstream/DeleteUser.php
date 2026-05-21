<?php

namespace App\Actions\Jetstream;

use App\Models\User;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
<<<<<<< HEAD
    public function delete(User $user): void
    {
=======
    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
>>>>>>> ec6237d (Third Week of Assignment small changes)
        $user->delete();
    }
}
