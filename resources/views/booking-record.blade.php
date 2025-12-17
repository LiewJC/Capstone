@extends('user')

@section('title', 'Booking History')

@section('content')
<div class="max-w-6xl mx-auto py-10">

    <h2 class="text-3xl font-bold text-primary-700 mb-8 text-center">Booking History</h2>

    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Booking Info</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Services</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Feedback</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <!-- Booking Info -->
                        <td class="px-4 py-4 text-sm text-gray-700 align-top">
                            <p><strong>Booking ID:</strong> {{ $booking->booking_id }}</p>
                            <p><strong>Store:</strong> {{ $booking->store->name ?? 'N/A' }}</p>
                            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->datetime)->format('d M Y') }}</p>
                            <p><strong>Time:</strong> {{ $booking->timeStart }} - {{ $booking->timeEnd }}</p>
                        </td>

                        <!-- Services -->
                        <td class="px-4 py-4 text-sm text-gray-700 align-top">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($booking->bookingServices as $bs)
                                    <li>{{ $bs->service->name ?? 'Service Deleted' }} - RM{{ number_format($bs->price, 2) }}</li>
                                @endforeach
                            </ul>
                        </td>

                        <!-- Status -->
                        <td class="px-4 py-4 text-sm text-gray-700 align-top">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                @if($booking->status === 'completed') bg-green-100 text-green-700
                                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($booking->status === 'confirmed') bg-blue-100 text-blue-700
                                @elseif($booking->status === 'cancelled') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>

                        <!-- Feedback -->
                        <td class="px-4 py-4 text-sm text-gray-700 align-top">
                            @if($booking->status === 'completed')
                                @if($booking->feedback)
                                    <div class="feedback-section">
                                        <div class="text-yellow-500 font-bold">⭐ {{ $booking->feedback->rating }}/5</div>
                                        <p class="mt-1 text-gray-600">{{ $booking->feedback->comment }}</p>
                                    </div>
                                @else
                                    <form action="{{ route('feedback.store') }}" method="POST" class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="booking_id" value="{{ $booking->booking_id }}">

                                        <div>
                                            <label for="rating-{{ $booking->booking_id }}" class="text-sm font-medium text-gray-700">Rating</label>
                                            <select name="rating" id="rating-{{ $booking->booking_id }}" required
                                                class="w-full border border-gray-300 rounded px-2 py-1 focus:ring-primary-500 focus:border-primary-500">
                                                <option value="">-- Select Rating --</option>
                                                @for($i = 5; $i >= 1; $i--)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div>
                                            <label for="comment-{{ $booking->booking_id }}" class="text-sm font-medium text-gray-700">Comment</label>
                                            <textarea name="comment" id="comment-{{ $booking->booking_id }}" rows="3" required
                                                class="w-full border border-gray-300 rounded px-2 py-1 focus:ring-primary-500 focus:border-primary-500"></textarea>
                                        </div>

                                        <button type="submit"
                                            class="bg-primary-500 hover:bg-primary-600 text-white font-medium px-3 py-1.5 rounded transition">
                                            Submit
                                        </button>
                                    </form>
                                @endif
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>

                        <!-- Action -->
                        <td class="px-4 py-4 text-sm text-gray-700 align-top">
                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                <form action="{{ route('booking.cancel', $booking->booking_id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white font-medium px-3 py-1.5 rounded transition">
                                        Cancel
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">No bookings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
