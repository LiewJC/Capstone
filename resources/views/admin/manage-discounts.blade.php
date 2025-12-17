@extends('admin.admin')

@section('title', 'Manage Discounts')

@section('content')
    <h1 class="text-3xl font-bold text-primary-700 mb-6">Manage Discounts</h1>

    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    <div class="mb-6">
        <a href="{{ route('admin.discounts.create') }}">
            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                Add New Discount
            </button>
        </a>
    </div>

    <form method="GET" action="{{ route('admin.manage-discount') }}" class="mb-6 flex space-x-3">
        <input type="text" name="search" placeholder="Search by code" value="{{ request('search') }}"
            class="border border-gray-300 rounded px-4 py-2 w-64">
        <button type="submit" class="bg-primary-500 text-white px-4 py-2 rounded hover:bg-primary-700">
            Search
        </button>
        @if(request('search'))
            <a href="{{ route('admin.manage-discount') }}" class="text-sm text-blue-500 hover:underline self-center">
                Clear
            </a>
        @endif
    </form>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-primary-100 text-primary-700">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">ID</th>
                    <th class="px-6 py-3 text-left font-semibold">Code</th>
                    <th class="px-6 py-3 text-left font-semibold">Percentage (%)</th>
                    <th class="px-6 py-3 text-left font-semibold">Valid Until</th>
                    <th class="px-6 py-3 text-left font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($discounts as $discount)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $discount->discount_id }}</td>
                        <td class="px-6 py-4">{{ $discount->code }}</td>
                        <td class="px-6 py-4">{{ $discount->percentage }}</td>
                        <td class="px-6 py-4">{{ $discount->valid_until }}</td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.discounts.edit', $discount->discount_id) }}"
                                class="text-blue-600 hover:underline text-sm"><button type="button"
                                    class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded">
                                    Edit
                                </button></a>

                            <form action="{{ route('admin.discounts.delete', $discount->discount_id) }}" method="POST"
                                class="inline" onsubmit="return confirm('Are you sure you want to delete this discount?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-500 px-6 py-4">No discounts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection