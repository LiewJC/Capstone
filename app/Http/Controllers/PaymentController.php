<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\BookingCart;
use App\Models\Store;
use App\Models\Discount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\BookingService;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    public function show(Request $request)
{
    $date = $request->date;
    $startTime = $request->start_time;
    $endTime = $request->end_time;

    if (!$date || !$startTime || !$endTime) {
        return redirect()->back()->with('error', 'Missing booking details.');
    }

    $user = auth()->user();
    $store = Store::find($user->selected_store_id);

    if (!$store) {
        return redirect()->back()->with('error', 'No store selected.');
    }

    $cartItems = BookingCart::with('service')
        ->where('user_id', $user->user_id)
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->back()->with('error', 'Your cart is empty.');
    }

    $vehicleInfo = $user->vehicle_info ?? '';

    return view('payment', [
        'date' => $date,
        'startTime' => $startTime,
        'endTime' => $endTime,
        'store' => $store,
        'cartItems' => $cartItems,
        'vehicleInfo' => $vehicleInfo,
    ]);
}


public function updatePaymentStatus(Request $request, $id)
{
    $request->validate([
        'payment_status' => 'required|in:pending,paid,refund'
    ]);

    $payment = Payment::findOrFail($id);
    $payment->payment_status = $request->payment_status;
    $payment->save();

    return back()->with('success', 'Payment status updated.');
}
public function applyDiscount(Request $request)
{
    $code = $request->input('discount_code');
    $user = auth()->user();

    $discount = Discount::where('code', $code)
        ->where('valid_until', '>=', now())
        ->first();

    if (!$discount) {
        return back()->with('error', 'Invalid or expired discount code.');
    }

    $alreadyUsed = DB::table('discount_user')
        ->where('user_id', $user->user_id)
        ->where('discount_id', $discount->discount_id)
        ->exists();

    if ($alreadyUsed) {
        return back()->with('error', 'You have already used this discount code.');
    }

    session([
        'discount' => [
            'id' => $discount->discount_id,
            'code' => $discount->code,
            'percentage' => $discount->percentage,
        ]
    ]);

    return back()->with('message', 'Discount applied!');
}


public function paypalSuccess(Request $request)
{
    $userId = Auth::id();
    
        $bookingData = [
        'user_id' => $userId,
        'store_id' => $request->query('store_id'),
        'datetime' => $request->query('date'),
        'timeStart' => $request->query('start_time'),
        'timeEnd' => $request->query('end_time'),
        'final_price' => $request->query('final_price'),
        'discount_code' => $request->query('discount_code')
    ];

    if (!$bookingData) {
        return redirect('/')->with('error', 'Booking data not found. Please try again.');
    }

    $booking = Booking::create([
        'user_id' => $bookingData['user_id'],
        'store_id' => $bookingData['store_id'],
        'datetime' => $bookingData['datetime'],
        'timeStart' => $bookingData['timeStart'],
        'timeEnd' => $bookingData['timeEnd'],
        'status' => 'pending',
    ]);

    $cartItems = BookingCart::where('user_id', $bookingData['user_id'])->get();

    foreach ($cartItems as $item) {
        BookingService::create([
            'booking_id' => $booking->booking_id,
            'service_id' => $item->service_id,
            'price' => $item->service->price,
        ]);
    }

    Payment::create([
        'booking_id' => $booking->booking_id,
        'amount' => $bookingData['final_price'],
        'payment_method' => 'PayPal',
        'payment_status' => 'Completed',
        'payment_date' => now(),
    ]);

    if ($bookingData['discount_code']) {
        $discount = DB::table('discounts')->where('code', $bookingData['discount_code'])->first();

        if ($discount) {
            $userId = $bookingData['user_id'];
            $discountId = $discount->discount_id;

            $alreadyUsed = DB::table('discount_user')
                ->where('user_id', $userId)
                ->where('discount_id', $discountId)
                ->exists();

            if (!$alreadyUsed) {
                DB::table('discount_user')->insert([
                    'user_id' => $userId,
                    'discount_id' => $discountId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    $this->sendBookingEmail($booking, $bookingData);

    BookingCart::where('user_id', $bookingData['user_id'])->delete();

    session()->forget('booking_data');
    session()->forget('discount');

    return view('payment_success');
}

public function handleCash(Request $request)
{
    $bookingData = [
        'user_id' => Auth::id(),
        'store_id' => $request->input('store_id'),
        'datetime' => $request->input('date'),
        'timeStart' => $request->input('start_time'),
        'timeEnd' => $request->input('end_time'),
        'final_price' => $request->input('final_price'),
        'discount_code' => $request->input('discount_code', null),
    ];

    $booking = Booking::create([
        'user_id' => $bookingData['user_id'],
        'store_id' => $bookingData['store_id'],
        'datetime' => $bookingData['datetime'],
        'timeStart' => $bookingData['timeStart'],
        'timeEnd' => $bookingData['timeEnd'],
        'status' => 'pending', 
    ]);

    $cartItems = BookingCart::where('user_id', $bookingData['user_id'])->get();

    foreach ($cartItems as $item) {
        BookingService::create([
            'booking_id' => $booking->booking_id,
            'service_id' => $item->service_id,
            'price' => $item->service->price,
        ]);
    }

    Payment::create([
        'booking_id' => $booking->booking_id,
        'amount' => $bookingData['final_price'],
        'payment_method' => 'Cash on the Spot',
        'payment_status' => 'Pending',
        'payment_date' => now(),
    ]);

    if ($bookingData['discount_code']) {
        $discount = DB::table('discounts')->where('code', $bookingData['discount_code'])->first();

        if ($discount) {
            $userId = $bookingData['user_id'];
            $discountId = $discount->discount_id;

            $alreadyUsed = DB::table('discount_user')
                ->where('user_id', $userId)
                ->where('discount_id', $discountId)
                ->exists();

            if (!$alreadyUsed) {
                DB::table('discount_user')->insert([
                    'user_id' => $userId,
                    'discount_id' => $discountId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    $this->sendBookingEmail($booking, $bookingData);

    BookingCart::where('user_id', $bookingData['user_id'])->delete();

    session()->forget('booking_data');
    session()->forget('discount');

    return view('payment_success');
}

public function handleCard(Request $request)
{
    $request->validate([
    'card_number' => ['required', 'digits:16'],
    'expiry_date' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'], // MM/YY
    'ccv' => ['required', 'digits:3'],
], [
    'card_number.required' => 'Please enter your card number.',
    'card_number.digits' => 'Card number must be exactly 16 digits.',
    'expiry_date.required' => 'Please enter your card expiry date.',
    'expiry_date.regex' => 'Expiry date format must be MM/YY (e.g. 04/27).',
    'ccv.required' => 'Please enter your card security code (CCV).',
    'ccv.digits' => 'CCV must be exactly 3 digits.',
]);


    $bookingData = [
        'user_id' => Auth::id(),
        'store_id' => $request->input('store_id'),
        'datetime' => $request->input('date'),
        'timeStart' => $request->input('start_time'),
        'timeEnd' => $request->input('end_time'),
        'final_price' => $request->input('final_price'),
        'discount_code' => $request->input('discount_code', null),
    ];

    $booking = Booking::create([
        'user_id' => $bookingData['user_id'],
        'store_id' => $bookingData['store_id'],
        'datetime' => $bookingData['datetime'],
        'timeStart' => $bookingData['timeStart'],
        'timeEnd' => $bookingData['timeEnd'],
        'status' => 'pending', 
    ]);

    $cartItems = BookingCart::where('user_id', $bookingData['user_id'])->get();
    foreach ($cartItems as $item) {
        BookingService::create([
            'booking_id' => $booking->booking_id,
            'service_id' => $item->service_id,
            'price' => $item->service->price,
        ]);
    }

    Payment::create([
        'booking_id' => $booking->booking_id,
        'amount' => $bookingData['final_price'],
        'payment_method' => 'Credit/Debit Card',
        'payment_status' => 'Completed',
        'payment_date' => now(),
    ]);

    if ($bookingData['discount_code']) {
        $discount = DB::table('discounts')->where('code', $bookingData['discount_code'])->first();
        if ($discount) {
            $userId = $bookingData['user_id'];
            $discountId = $discount->discount_id;
            $alreadyUsed = DB::table('discount_user')
                ->where('user_id', $userId)
                ->where('discount_id', $discountId)
                ->exists();

            if (!$alreadyUsed) {
                DB::table('discount_user')->insert([
                    'user_id' => $userId,
                    'discount_id' => $discountId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    $this->sendBookingEmail($booking, $bookingData);

    BookingCart::where('user_id', $bookingData['user_id'])->delete();
    session()->forget(['booking_data', 'discount']);

    return view('payment_success');
}

public function handleEwallet(Request $request)
{
    $request->validate([
        'ewallet_type' => 'required|string',
    ]);

    $bookingData = [
        'user_id' => Auth::id(),
        'store_id' => $request->input('store_id'),
        'datetime' => $request->input('date'),
        'timeStart' => $request->input('start_time'),
        'timeEnd' => $request->input('end_time'),
        'final_price' => $request->input('final_price'),
        'discount_code' => $request->input('discount_code', null),
    ];

    $booking = Booking::create([
        'user_id' => $bookingData['user_id'],
        'store_id' => $bookingData['store_id'],
        'datetime' => $bookingData['datetime'],
        'timeStart' => $bookingData['timeStart'],
        'timeEnd' => $bookingData['timeEnd'],
        'status' => 'pending',
    ]);

    $cartItems = BookingCart::where('user_id', $bookingData['user_id'])->get();
    foreach ($cartItems as $item) {
        BookingService::create([
            'booking_id' => $booking->booking_id,
            'service_id' => $item->service_id,
            'price' => $item->service->price,
        ]);
    }

    Payment::create([
        'booking_id' => $booking->booking_id,
        'amount' => $bookingData['final_price'],
        'payment_method' => $request->input('ewallet_type'),
        'payment_status' => 'Completed',
        'payment_date' => now(),
    ]);

    if ($bookingData['discount_code']) {
        $discount = DB::table('discounts')->where('code', $bookingData['discount_code'])->first();
        if ($discount) {
            $userId = $bookingData['user_id'];
            $discountId = $discount->discount_id;
            $alreadyUsed = DB::table('discount_user')
                ->where('user_id', $userId)
                ->where('discount_id', $discountId)
                ->exists();

            if (!$alreadyUsed) {
                DB::table('discount_user')->insert([
                    'user_id' => $userId,
                    'discount_id' => $discountId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    $this->sendBookingEmail($booking, $bookingData);

    BookingCart::where('user_id', $bookingData['user_id'])->delete();
    session()->forget(['booking_data', 'discount']);

    return view('payment_success');
}


private function sendBookingEmail($booking, $bookingData)
{
    $cartItems = BookingCart::where('user_id', $bookingData['user_id'])->get();
    $user = Auth::user();
    $vehicleInfo = $user->vehicle_info ?? 'Not provided';
    $payment = Payment::where('booking_id', $booking->booking_id)->first();
    $paymentMethod = $payment->payment_method ?? 'N/A';
    $paymentStatus = $payment->payment_status ?? 'N/A';

    $htmlContent = '
        <html>
        <head>
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                    font-family: Arial, sans-serif;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
                h2 {
                    color: #333;
                }
            </style>
        </head>
        <body>
            <h2>Booking Confirmation</h2>
            <p>Dear Valued Customer,</p>
            <p>Thank you for your booking. Here are the details:</p>
            <table>
                <tr><th>Booking ID</th><td>' . htmlspecialchars($booking->booking_id) . '</td></tr>
                <tr><th>Vehicle Info</th><td>' . nl2br(htmlspecialchars($vehicleInfo)) . '</td></tr>
                <tr><th>Store</th><td>' . htmlspecialchars($booking->store->name ?? 'N/A') . '</td></tr>
                <tr><th>Date</th><td>' . htmlspecialchars($booking->datetime) . '</td></tr>
                <tr><th>Start Time</th><td>' . htmlspecialchars($booking->timeStart) . '</td></tr>
                <tr><th>End Time</th><td>' . htmlspecialchars($booking->timeEnd) . '</td></tr>
                <tr><th>Status</th><td>' . htmlspecialchars($booking->status) . '</td></tr>
                <tr><th>Total Price</th><td>RM ' . number_format($bookingData['final_price'], 2) . '</td></tr>
                <tr><th>Payment Method</th><td>' . htmlspecialchars($paymentMethod) . '</td></tr>
                <tr><th>Payment Status</th><td>' . htmlspecialchars($paymentStatus) . '</td></tr>
            </table>

            <h3>Services</h3>
            <table>
                <thead>
                    <tr><th>Service Name</th><th>Price (RM)</th></tr>
                </thead>
                <tbody>';

    foreach ($cartItems as $item) {
        $htmlContent .= '<tr><td>' . htmlspecialchars($item->service->name) . '</td><td>RM ' . number_format($item->service->price, 2) . '</td></tr>';
    }

    $htmlContent .= '
                </tbody>
            </table>
            <p>We look forward to serving you!</p>
        </body>
        </html>';

    $userEmail = Auth::user()->email;

    Mail::send([], [], function ($message) use ($userEmail, $htmlContent) {
        $message->to($userEmail)
            ->subject('Booking Confirmation')
            ->html($htmlContent);
    });
}

public function updateVehicleInfo(Request $request)
{
    $request->validate([
        'vehicle_info' => 'required|string|max:255',
    ]);

    $user = auth()->user();
    $user->vehicle_info = $request->vehicle_info;
    $user->save();

    return back()->with('vehicle_success', 'Vehicle information updated successfully.');
}


}
