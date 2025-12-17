<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $users = User::query();

    if ($search) {
        $users = $users->where(function ($query) use ($search) {
            $query->where('user_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
        });
    }

    $users = $users->get();

    return view('admin.dashboard', compact('users', 'search'));
}

public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }

    public function toggleActiveStatus($id)
    {
        $user = User::findOrFail($id);
        $user->active_status = !$user->active_status;
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'User status updated.');
    }

public function updateProfile(Request $request)
{
    $request->validate([
        'user_name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'vehicle_info' => 'required|string|max:255',
    ]);

    $user = Auth::user(); 

    $user->user_name = $request->user_name;
    $user->phone = $request->phone;
    $user->vehicle_info = $request->vehicle_info;
    $user->save();

    return back()->with('success', 'Profile updated successfully.');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = Auth::user(); 

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->with('error', 'Current password is incorrect.');
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Password updated successfully.');
}


}
