<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\RespondsWithApiJson;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ApiProfileController extends Controller
{
    use RespondsWithApiJson;

    public function show()
    {
        return $this->apiResource(
            new UserResource(Auth::user()->loadCount('vehicles')),
            'Profile loaded.'
        );
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,'.$user->id,
        ]);

        $user->update($validated);
        ActivityLogger::log('profile.updated', 'Profile information updated', $user);

        return $this->apiResource(
            new UserResource($user->fresh()->loadCount('vehicles')),
            'Profile updated successfully.'
        );
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return $this->apiError('The given data was invalid.', 422, [
                'current_password' => ['Current password is incorrect.'],
            ]);
        }

        $user->update(['password' => Hash::make($request->password)]);
        ActivityLogger::log('profile.password', 'Password updated', $user);

        return $this->apiSuccess(null, 'Password updated successfully.');
    }
}
