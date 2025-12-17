@extends('admin.admin')

@section('title', 'Manage Services')

@section('content')
    <h1 class="text-3xl font-bold text-primary-700 mb-6">Manage Services</h1>

    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    <div class="mb-6">
        <a href="{{ route('admin.services.create') }}">
            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                Add New Service
            </button>
        </a>
    </div>

    <form method="GET" action="{{ route('admin.service') }}" class="mb-6 flex space-x-3">
        <input type="text" name="search" placeholder="Search by name" value="{{ request('search') }}"
            class="border border-gray-300 rounded px-4 py-2 w-64">
        <button type="submit" class="bg-primary-500 text-white px-4 py-2 rounded hover:bg-primary-700">
            Search
        </button>
        @if(request('search'))
            <a href="{{ route('admin.service') }}" class="text-sm text-blue-500 hover:underline self-center">
                Clear
            </a>
        @endif
    </form>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-primary-100 text-primary-700">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">Image</th>
                    <th class="px-6 py-3 text-left font-semibold">Name</th>
                    <th class="px-6 py-3 text-left font-semibold">Description</th>
                    <th class="px-6 py-3 text-left font-semibold">Price (RM)</th>
                    <th class="px-6 py-3 text-left font-semibold">Duration</th>
                    <th class="px-6 py-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($services as $service)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <img src="{{ asset($service->image_url) }}" alt="Service Image" class="w-24 h-auto rounded border">
                        </td>
                        <td class="px-6 py-4">{{ $service->name }}</td>
                        <td class="px-6 py-4">{{ $service->description }}</td>
                        <td class="px-6 py-4">RM {{ number_format($service->price, 2) }}</td>
                        <td class="px-6 py-4">{{ $service->duration }}</td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.services.edit', $service->service_id) }}"
                                class="text-blue-600 hover:underline text-sm">
                                <button type="button"
                                    class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded">
                                    Edit
                                </button></a>
                            <form action="{{ route('admin.services.destroy', $service->service_id) }}" method="POST"
                                class="inline" onsubmit="return confirm('Delete this service?');">
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
                        <td colspan="6" class="text-center text-gray-500 px-6 py-4">No services found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection