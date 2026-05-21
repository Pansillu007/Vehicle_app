<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    // List users with optional search filtering, requiring admin authorization.
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"))
            ->withCount('vehicles')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // Delete a user account after admin authorization and log the event.
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();
        ActivityLogger::log('admin.user_deleted', 'User '.$user->email.' was deleted');

        return back()->with('success', 'User deleted successfully.');
    }
}
