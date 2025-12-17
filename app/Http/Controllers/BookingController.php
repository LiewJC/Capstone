<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Booking;
use App\Models\BookingCart;
use App\Models\Service;


class BookingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $bookings = Booking::with(['user', 'store', 'feedback', 'bookingServices.service'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', fn($q) => $q->where('user_name', 'like', "%$search%"))
                    ->orWhereHas('store', fn($q) => $q->where('name', 'like', "%$search%"));
            })
            ->orderBy('booking_id', 'asc')
            ->get();


        return view('admin.manage-bookings', compact('bookings'));
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,confirmed,completed,cancelled',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        return redirect()->back()->with('success', 'Booking status updated.');
    }
    public function destroy($id)
    {
        Booking::destroy($id);
        return redirect()->back()->with('success', 'Booking deleted.');
    }

    public function showSelectDate()
    {
        $user = Auth::user();

        if (!$user->selected_store_id) {
            return redirect()->route('cart.index')->with('error', 'Please select a store first.');
        }

        $storeId = $user->selected_store_id;

        $schedules = Schedule::where('store_id', $storeId)->get();

        $bookings = Booking::where('store_id', $storeId)
            ->whereDate('datetime', '>=', now()->toDateString())
            ->where('status', '!=', 'cancelled')
            ->get();

        $cartItems = BookingCart::where('user_id', Auth::id())->get();
        $services = Service::all();

        return view('select-date', [
            'schedules' => $schedules,
            'bookings' => $bookings,
            'cartItems' => $cartItems,
            'services' => $services,
        ]);
    }

    public function cancel($id)
{
    $booking = Booking::where('booking_id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    if (!in_array($booking->status, ['pending', 'confirmed'])) {
        return back()->with('error', 'Cannot cancel this booking.');
    }

    $booking->status = 'cancelled';
    $booking->save();

    return back()->with('success', 'Booking cancelled.');
}


    public function userBookingRecord()
    {
        $bookings = Booking::with(['store', 'bookingServices.service', 'feedback'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('booking-record', compact('bookings'));

    }
}
