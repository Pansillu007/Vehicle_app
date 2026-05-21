<?php

namespace App\Actions\Fortify;

<<<<<<< HEAD
=======
use Illuminate\Contracts\Validation\Rule;
>>>>>>> ec6237d (Third Week of Assignment small changes)
use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
<<<<<<< HEAD
=======
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, Rule|array<mixed>|string>
     */
>>>>>>> ec6237d (Third Week of Assignment small changes)
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::default(), 'confirmed'];
    }
}
