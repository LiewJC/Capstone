<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\Booking;

class FeedbackController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'booking_id' => 'required|exists:bookings,booking_id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|max:1000',
    ]);

    $booking = Booking::where('booking_id', $request->booking_id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    if ($booking->feedback) {
        return back()->with('error', 'You have already submitted feedback.');
    }

    $feedback = new Feedback([
        'user_id' => auth()->id(),
        'booking_id' => $booking->booking_id,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    $feedback->save();

    $booking->feedback_id = $feedback->id;
    $booking->save();

    return back()->with('success', 'Feedback submitted successfully.');
}

    public function destroy($id)
{
    Feedback::destroy($id);
    return redirect()->back()->with('success', 'Feedback deleted.');
}

}
