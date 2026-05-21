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
                <table class="premium-table">
                    <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Vehicles</th><th class="text-right">Actions</th></tr></thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="text-xs px-2 py-1 rounded-full {{ $user->isAdmin() ? 'bg-blue-500/20 text-blue-400' : 'bg-gray-500/20 text-gray-400' }}">{{ $user->role?->value }}</span></td>
                            <td>{{ $user->vehicles_count }}</td>
                            <td class="text-right">
                                @can('delete', $user)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger text-xs">Delete</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $users->links() }}</div>
        </div>
    </div>
</x-app-layout>
