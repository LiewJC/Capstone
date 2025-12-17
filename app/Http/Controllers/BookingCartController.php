<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BookingCart;
use App\Models\Service;

class BookingCartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,service_id',
        ]);

        $user = Auth::user();

        $service = Service::findOrFail($request->service_id);

        $existing = BookingCart::where('user_id', $user->user_id)
            ->where('service_id', $service->service_id)
            ->first();

        if ($existing) {
            return back()->with('message', 'Service is already in your cart.');
        }

        $dd = BookingCart::create([
            'user_id' => $user->user_id,
            'service_id' => $service->service_id,
            'total_price' => $service->price,
        ]);

        return back()->with('message', 'Service added to cart!');
    }

    public function index()
    {
        $user = Auth::user();

        $cartItems = BookingCart::with('service')
            ->where('user_id', $user->user_id)
            ->get();

        $total = $cartItems->sum('total_price');

        return view('booking-cart', compact('cartItems', 'total'));
    }

    public function remove($cart_id)
    {
        $item = BookingCart::findOrFail($cart_id);

        if ($item->user_id !== Auth::user()->user_id) {
            abort(403);
        }

        $item->delete();

        return back()->with('message', 'Item removed from cart.');
    }
}
