@extends('admin.admin')

@section('title', 'Manage Bookings')

@section('content')
    <h1 class="text-3xl font-bold text-primary-700 mb-6">Manage Bookings</h1>

    @if(session('success'))
        <script>alert("{{ session('success') }}");</script> 
    @endif

    <form method="GET" action="{{ route('admin.manage-booking') }}" class="mb-6 flex space-x-3">
        <input
            type="text"
            name="search"
            placeholder="Search by user or store"
            value="{{ request('search') }}"
            class="border border-gray-300 rounded px-4 py-2 w-64"
        >
        <button type="submit" class="bg-primary-500 text-white px-4 py-2 rounded hover:bg-primary-700">
            Search
        </button>
        @if(request('search'))
            <a href="{{ route('admin.manage-booking') }}" class="text-sm text-blue-500 hover:underline self-center">
                Clear
            </a>
        @endif
    </form>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-primary-100 text-primary-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">ID</th>
                    <th class="px-4 py-3 text-left font-semibold">User</th>
                    <th class="px-4 py-3 text-left font-semibold">Store</th>
                    <th class="px-4 py-3 text-left font-semibold">Date & Time</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Feedback</th>
                    <th class="px-4 py-3 text-left font-semibold">Services</th>
                    <th class="px-4 py-3 text-left font-semibold">Payment</th>
                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 align-top">
                        <td class="px-4 py-3">{{ $booking->booking_id }}</td>
                        <td class="px-4 py-3">{{ $booking->user->user_name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $booking->store->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($booking->datetime)->format('Y-m-d') }}<br>
                            <span class="text-xs text-gray-500">({{ $booking->timeStart }} to {{ $booking->timeEnd }})</span>
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.bookings.updateStatus', $booking->booking_id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="border rounded px-2 py-1">
                                    <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            @if($booking->feedback)
                                <strong>Rating:</strong> {{ $booking->feedback->rating }}<br>
                                <strong>Comment:</strong> {{ $booking->feedback->comment }}<br>
                                <form action="{{ route('admin.feedbacks.destroy', $booking->feedback->feedback_id) }}" method="POST" onsubmit="return confirm('Delete this feedback?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 text-sm mt-1 hover:underline">Delete Feedback</button>
                                </form>
                            @else
                                <em class="text-gray-500">No feedback</em>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @forelse($booking->bookingServices as $bs)
                                <div>
                                    • {{ $bs->service->name ?? 'Service deleted' }} - RM {{ number_format($bs->price, 2) }}
                                </div>
                            @empty
                                <em class="text-gray-500">No services</em>
                            @endforelse
                        </td>
                        <td class="px-4 py-3">
                            @if($booking->payment)
                                <strong>Method:</strong> {{ $booking->payment->payment_method }}<br>
                                <strong>Status:</strong>
                                <form action="{{ route('admin.payments.updateStatus', $booking->payment->payment_id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="payment_status" onchange="this.form.submit()" class="border rounded px-2 py-1 mt-1">
                                        <option value="pending" {{ $booking->payment->payment_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ $booking->payment->payment_status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="refund" {{ $booking->payment->payment_status == 'refund' ? 'selected' : '' }}>Refund</option>
                                    </select>
                                </form>
                                <strong>Amount:</strong> RM {{ number_format($booking->payment->amount, 2) }}
                            @else
                                <em class="text-gray-500">No payment info</em>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.bookings.destroy', $booking->booking_id) }}" method="POST" onsubmit="return confirm('Delete this booking?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm mt-1">
                                    Delete Booking
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-gray-500 px-4 py-4">No bookings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
