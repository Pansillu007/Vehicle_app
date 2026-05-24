<x-app-layout>
    <x-slot name="header">
        <h2 class="page-header-title">Manage Users</h2>
    </x-slot>
    <div class="page-container">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" class="glass-card rounded-2xl p-4 mb-6 flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-input-dark flex-1" placeholder="Search users...">
                <button type="submit" class="btn-primary">Search</button>
            </form>
            <div class="glass-card rounded-3xl overflow-hidden">
                <div class="table-container">
                    <table class="premium-table min-w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 sm:px-6">User</th>
                                <th class="hide-on-mobile">Email</th>
                                <th class="hide-on-mobile">Role</th>
                                <th class="hide-on-mobile text-center">Vehicles</th>
                                <th class="text-right px-4 py-3 sm:px-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td class="px-4 py-4 sm:px-6 font-bold text-gray-900 dark:text-white">
                                    {{ $user->name }}
                                    <div class="sm:hidden text-[10px] font-medium text-gray-500 mt-0.5">{{ $user->email }}</div>
                                </td>
                                <td class="hide-on-mobile">{{ $user->email }}</td>
                                <td class="hide-on-mobile"><span class="badge-blue">{{ $user->role?->value ?? $user->role }}</span></td>
                                <td class="hide-on-mobile text-center font-medium">{{ $user->vehicles_count }}</td>
                                <td class="text-right px-4 py-4 sm:px-6">
                                    @can('delete', $user)
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 text-xs font-bold hover:underline">Delete</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400">
                                    No users match your search.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6">{{ $users->links() }}</div>
        </div>
    </div>
</x-app-layout>
