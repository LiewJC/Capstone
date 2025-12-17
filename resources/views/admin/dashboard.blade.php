@extends('admin.admin')
@section('content')
            <h1 class="text-3xl font-bold text-primary-700 mb-6">User Management</h1>

            @if(session('success'))
                <script>alert("{{ session('success') }}");</script>
            @endif

            <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-6 flex space-x-3">
                <input
                    type="text"
                    name="search"
                    placeholder="Search by name or email"
                    value="{{ $search ?? '' }}"
                    class="border border-gray-300 rounded px-4 py-2 w-64"
                >
                <button type="submit" class="bg-primary-500 text-white px-4 py-2 rounded hover:bg-primary-700">
                    Search
                </button>
                @if(!empty($search))
                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-500 hover:underline self-center">Clear</a>
                @endif
            </form>

            <div class="overflow-x-auto bg-white rounded shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-100 text-primary-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Phone</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Vehicle Info</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $user->user_id }}</td>
                                <td class="px-6 py-4">{{ $user->user_name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">{{ $user->phone }}</td>
                                <td class="px-6 py-4">{{ $user->vehicle_info }}</td>
                                <td class="px-6 py-4">
                                    @if($user->active_status)
                                        <span class="text-green-600 font-medium">Active</span>
                                    @else
                                        <span class="text-red-600 font-medium">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <form action="{{ route('admin.users.toggleStatus', $user->user_id ?? $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">
                                            {{ $user->active_status ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.destroy', $user->user_id ?? $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        @if($users->isEmpty())
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No users found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

@endsection
