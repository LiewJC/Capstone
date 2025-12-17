@extends('user')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto py-10">

    <h1 class="text-3xl font-bold text-primary-700 mb-8 text-center">User Login</h1>

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

    <form method="POST" action="{{ route('login.post') }}" class="bg-white shadow rounded-lg p-6 space-y-6">
        @csrf

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
            <button type="submit"
                class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-2 rounded transition">
                Login
            </button>
        </div>
    </form>

    <p class="mt-6 text-center text-gray-600">
        Don't have an account? 
        <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium">Register here</a>
    </p>

</div>
@endsection
