@extends('admin.admin')

@section('title', 'Manage Stores')

@section('content')

    <h1 class="text-3xl font-bold text-primary-700 mb-6">Manage Stores</h1>

    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <div class="mb-6">
        <a href="{{ route('admin.stores.create') }}">
            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                Add New Store
            </button>
        </a>
    </div>

    <form method="GET" action="{{ route('admin.manage-store') }}" class="mb-6 flex space-x-3">
        <input type="text" name="search" placeholder="Search by name or address" value="{{ $search ?? '' }}"
            class="border border-gray-300 rounded px-4 py-2 w-64">
        <button type="submit" class="bg-primary-500 text-white px-4 py-2 rounded hover:bg-primary-700">
            Search
        </button>
        @if(!empty($search))
            <a href="{{ route('admin.manage-store') }}" class="text-sm text-blue-500 hover:underline self-center">
                Clear
            </a>
        @endif
    </form>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-primary-100 text-primary-700">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">ID</th>
                    <th class="px-6 py-3 text-left font-semibold">Name</th>
                    <th class="px-6 py-3 text-left font-semibold w-64">Address</th> 
                    <th class="px-6 py-3 text-left font-semibold">Contact</th>
                    <th class="px-6 py-3 text-left font-semibold">Status</th>
                    <th class="px-6 py-3 text-left font-semibold">Operation Hours</th>
                    <th class="px-6 py-3 text-left font-semibold">Schedules</th>
                    <th class="px-6 py-3 text-left font-semibold w-40">Actions</th> 
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($stores as $store)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $store->store_id }}</td>
                        <td class="px-6 py-4">{{ $store->name }}</td>
                        <td class="px-6 py-4 w-64 whitespace-normal break-words">
                            {{ $store->address }}
                        </td>
                        <td class="px-6 py-4">{{ $store->contact_number }}</td>
                        <td class="px-6 py-4">{{ ucfirst($store->status) }}</td>
                        <td class="px-6 py-4">{{ $store->operation_hours }}</td>
                        <td class="px-6 py-4 space-y-1">
                            @foreach(\App\Models\Schedule::where('store_id', $store->store_id)->get() as $schedule)
                                <div>{{ $schedule->day_of_week }} ({{ $schedule->start_time }} - {{ $schedule->end_time }})</div>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 w-40 space-y-2">
                            <a href="{{ route('admin.stores.edit', $store->store_id) }}">
                                <button type="button"
                                    class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded w-full">
                                    Edit
                                </button>
                            </a>
                            <form action="{{ route('admin.stores.destroy', $store->store_id) }}" method="POST"
                                onsubmit="return confirm('Delete this store?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded w-full">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-500 px-6 py-4">No stores found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection