<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function register(Request $request)
{
    $messages = [
        'password.regex' => 'Password must include at least one uppercase letter, one lowercase letter, one digit, and one special character.',
    ];

    $request->validate([
        'user_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'password' => [
        'required',
        'string',
        'min:8',
        'confirmed',
        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
    ],
        'phone' => 'nullable|string',
        'vehicle_info' => 'nullable|string',
    ],$messages);

    $existingUser = User::where('email', $request->email)->first();

    if ($existingUser) {
        if (!$existingUser->email_verified) {
            $otp = rand(100000, 999999);
            $existingUser->otp = $otp;
            $existingUser->save();

            Mail::raw("Your verification code is: $otp", function ($message) use ($existingUser) {
                $message->to($existingUser->email)
                        ->subject('Email Verification OTP');
            });

            session(['pending_user_id' => $existingUser->user_id]);

            return redirect()->route('verify.otp')->with('message', 'Please verify your email with the new OTP sent.');
        } else {
            return back()->withErrors(['email' => 'This email is already registered and verified. Please login.']);
        }
    }

    $otp = rand(100000, 999999);

    $user = User::create([
        'user_name' => $request->user_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'phone' => $request->phone,
        'vehicle_info' => $request->vehicle_info,
        'otp' => $otp,
        'email_verified' => false,
        'active_status' => false,
        'selected_store_id' => null,
    ]);

    Mail::raw("Your verification code is: $otp", function ($message) use ($user) {
        $message->to($user->email)
                ->subject('Email Verification OTP');
    });

    session(['pending_user_id' => $user->user_id]);

    return redirect()->route('verify.otp')->with('message', 'An OTP has been sent to your email for verification.');
}


public function showOtpForm()
{
    if (!session('pending_user_id')) {
        return redirect()->route('register')->with('message', 'Please register first.');
    }

    return view('verify-otp');
}

public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|digits:6',
    ]);

    $user = User::find(session('pending_user_id'));

    if (!$user) {
        return redirect()->route('register')->with('message', 'User not found.');
    }

    if ($user->otp !== $request->otp) {
        return back()->withErrors(['otp' => 'Invalid OTP.']);
    }

    $user->otp = null; 
    $user->email_verified = true;
    $user->active_status = true;
    $user->save();

    session()->forget('pending_user_id');

    return redirect()->route('login')->with('message', 'Email verified! You can now login.');
}

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    if (!$user->active_status) {
        throw ValidationException::withMessages([
            'email' => ['Your account is inactive. Please contact support.'],
        ]);
    }

    Auth::login($user);

    return redirect('/');
}

}
