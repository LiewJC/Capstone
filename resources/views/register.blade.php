@extends('user')

@section('title', 'Register')

@section('content')
<div class="max-w-md mx-auto py-10">

    <h1 class="text-3xl font-bold text-primary-700 mb-8 text-center">User Registration</h1>

    @if(session('message'))
        <script>
            alert("{{ session('message') }}");
        </script>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}" class="bg-white shadow rounded-lg p-6 space-y-6">
        @csrf

        <div>
            <label for="user_name" class="block text-sm font-medium text-gray-700">User Name:</label>
            <input type="text" name="user_name" id="user_name" value="{{ old('user_name') }}" required
                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
            <input type="password" name="password" id="password" required
                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone:</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>

        <div>
            <label for="vehicle_info" class="block text-sm font-medium text-gray-700">Vehicle Info:</label>
            <input type="text" name="vehicle_info" id="vehicle_info" value="{{ old('vehicle_info') }}"
                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>

        <div>
            <button type="submit"
                class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-2 rounded transition">
                Register
            </button>
        </div>
    </form>

    <p class="mt-6 text-center text-gray-600">
        Already have an account? 
        <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium">Login here</a>
    </p>

</div>
@endsection
