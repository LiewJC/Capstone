@extends('admin.admin')

@section('title', 'Add Discount')

@section('content')
<div class="flex flex-col items-center justify-center min-h-full py-12 px-4">

    <h2 class="text-3xl font-bold text-primary-700 mb-8 text-center">Add New Discount</h2>

    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if ($errors->any())
        <div class="mb-6 max-w-lg w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.discounts.store') }}" class="max-w-lg w-full bg-white rounded shadow p-6 space-y-6">
        @csrf

        <div>
            <label for="code" class="block font-semibold text-gray-700 mb-1">Discount Code:</label>
            <input type="text" id="code" name="code" value="{{ old('code') }}" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <div>
            <label for="percentage" class="block font-semibold text-gray-700 mb-1">Discount Percentage (%):</label>
            <input type="number" id="percentage" name="percentage" value="{{ old('percentage') }}" min="1" max="100" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <div>
            <label for="valid_until" class="block font-semibold text-gray-700 mb-1">Valid Until:</label>
            <input type="date" id="valid_until" name="valid_until" value="{{ old('valid_until') }}" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <button type="submit"
            class="bg-primary-500 hover:bg-primary-700 text-white font-semibold px-6 py-2 rounded w-full transition">
            Add Discount
        </button>
    </form>

    

</div>
@endsection
