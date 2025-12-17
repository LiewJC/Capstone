@extends('admin.admin')

@section('title', 'Admin Profile')

@section('content')
<div class="flex flex-col items-center justify-center min-h-full py-12">

    <h2 class="text-3xl font-bold text-primary-700 mb-6 text-center">Admin Profile</h2>

    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    <div class="max-w-lg w-full bg-white rounded shadow p-6 mb-8">
        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="admin_name" class="block font-semibold text-gray-700 mb-1 text-left">Name:</label>
                <input type="text" id="admin_name" name="admin_name"
                    value="{{ old('admin_name', $admin->admin_name) }}"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
            </div>

            <div>
                <label for="phone" class="block font-semibold text-gray-700 mb-1 text-left">Phone:</label>
                <input type="text" id="phone" name="phone"
                    value="{{ old('phone', $admin->phone) }}"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1 text-left">Email:</label>
                <input type="text" value="{{ $admin->email }}" disabled
                    class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 cursor-not-allowed"
                >
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1 text-left">Role:</label>
                <input type="text" value="{{ ucfirst($admin->role) }}" disabled
                    class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 cursor-not-allowed"
                >
            </div>

            <button type="submit"
                class="bg-primary-500 hover:bg-primary-700 text-white font-semibold px-4 py-2 rounded transition w-full">
                Update Profile
            </button>
        </form>
    </div>

    <hr class="my-10 border-gray-300 w-full max-w-lg">

    <div class="max-w-lg w-full bg-white rounded shadow p-6">
        <h3 class="text-xl font-bold text-primary-700 mb-6 text-center">Change Password</h3>

        <form method="POST" action="{{ route('admin.profile.update-password') }}" class="space-y-4">
            @csrf

            <div>
                <label for="current_password" class="block font-semibold text-gray-700 mb-1 text-left">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
            </div>

            <div>
                <label for="new_password" class="block font-semibold text-gray-700 mb-1 text-left">New Password:</label>
                <input type="password" id="new_password" name="new_password" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
            </div>

            <div>
                <label for="new_password_confirmation" class="block font-semibold text-gray-700 mb-1 text-left">Confirm New Password:</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
            </div>

            <button type="submit"
                class="bg-primary-500 hover:bg-primary-700 text-white font-semibold px-4 py-2 rounded transition w-full">
                Change Password
            </button>
        </form>
    </div>
</div>
@endsection
