<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Payment;
use App\Models\Discount;
use App\Models\BookingCart;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StripePaymentController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        session([
            'booking_data' => [
                'user_id' => Auth::id(),
                'store_id' => $request->input('store_id'),
                'datetime' => $request->input('date'),
                'timeStart' => $request->input('start_time'),
                'timeEnd' => $request->input('end_time'),
                'final_price' => $request->input('final_price'),
                'discount_code' => $request->input('discount_code', null),
            ]
        ]);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'myr',
                        'product_data' => [
                            'name' => 'Service Booking',
                        ],
                        'unit_amount' => $request->final_price * 100, // convert RM to cents
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        $bookingData = session('booking_data');

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
            'payment_method' => 'Credit Card/ Debit Card (Stripe)',
            'payment_status' => 'Completed',
            'payment_date' => Carbon::now(),
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
        <p>Dear Valued Customer, </p>
        <p>Thank you for your booking. Here are the details:</p>
        <table>
            <tr><th>Booking ID</th><td>' . htmlspecialchars($booking->booking_id) . '</td></tr>
            <tr><th>Store</th><td>' . htmlspecialchars($booking->store->name ?? 'N/A') . '</td></tr>
            <tr><th>Date</th><td>' . htmlspecialchars($booking->datetime) . '</td></tr>
            <tr><th>Start Time</th><td>' . htmlspecialchars($booking->timeStart) . '</td></tr>
            <tr><th>End Time</th><td>' . htmlspecialchars($booking->timeEnd) . '</td></tr>
            <tr><th>Status</th><td>' . htmlspecialchars($booking->status) . '</td></tr>
            <tr><th>Total Price</th><td>RM ' . number_format($bookingData['final_price'], 2) . '</td></tr>
        </table>

                <h3>Services</h3>
        <table>
            <thead>
                <tr><th>Service Name</th><th>Price (RM)</th></tr>
            </thead>
            <tbody>';

        $cartItems = BookingCart::where('user_id', $bookingData['user_id'])->get();

        foreach ($cartItems as $item) {
            $htmlContent .= '<tr><td>' . htmlspecialchars($item->service->name) . '</td><td>RM ' . number_format($item->service->price, 2) . '</td></tr>';
        }

        $htmlContent .= '
            </tbody>
        </table>

        <p>We look forward to serving you!</p>
    </body>
    </html>
';

        $userEmail = Auth::user()->email;

        Mail::send([], [], function ($message) use ($userEmail, $htmlContent) {
            $message->to($userEmail)
                ->subject('Booking Confirmation')
                ->html($htmlContent);
        });

        BookingCart::where('user_id', $bookingData['user_id'])->delete();

        session()->forget('booking_data');
        session()->forget('discount');

        return view('payment_success');
    }


    public function cancel()
    {
        return view('payment_cancel');
    }
}
