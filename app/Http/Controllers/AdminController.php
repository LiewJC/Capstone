<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    $query = Admin::query();

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('admin_name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%');
        });
    }

    $admins = $query->get();

    return view('admin.manage-admins', compact('admins'));
}
    public function showLoginForm()
    {
        return view('admin.admin-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->admin_password)) {
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        session(['admin_id' => $admin->admin_id]);
        session(['admin_email' => $admin->email]); 
        session(['role' => $admin->role]);
        return redirect()->route('admin.dashboard');
    }

    public function showAddForm()
    {
        return view('admin.add-admin');
    }

    public function addAdmin(Request $request)
    {
        $messages = [
        'admin_password.regex' => 'Password must include at least one uppercase letter, one lowercase letter, one digit, and one special character.',
    ];

        $request->validate([
            'admin_name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'admin_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],
            'role' => 'required|string',
            'phone' => 'nullable|string'
        ], $messages);


        Admin::create([
            'admin_name' => $request->admin_name,
            'email' => $request->email,
            'admin_password' => Hash::make($request->admin_password),
            'role' => $request->role,
            'phone' => $request->phone
        ]);

        return back()->with('success', 'Admin added successfully.');
    }

    public function updateRole(Request $request, $id)
{
    $admin = Admin::findOrFail($id);
    $admin->role = $request->input('role');
    $admin->save();

    return redirect()->route('admin.manage-admin')->with('success', 'Role updated successfully.');
}

public function delete($id)
{
    $admin = Admin::findOrFail($id);

    if ($admin->admin_id == session('admin_id')) {
        return redirect()->route('admin.manage-admin')->with('success', 'You cannot delete yourself.');
    }

    $admin->delete();
    return redirect()->route('admin.manage-admin')->with('success', 'Admin deleted successfully.');
}

    public function logout(Request $request)
{
    $request->session()->forget('admin_id');
    $request->session()->forget('admin_email');
    $request->session()->flush();

    return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
}

public function updateProfile(Request $request)
{
    $request->validate([
        'admin_name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
    ]);

    $admin = Admin::findOrFail(session('admin_id'));

    $admin->admin_name = $request->admin_name;
    $admin->phone = $request->phone;
    $admin->save();

    return back()->with('success', 'Profile updated successfully.');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $admin = Admin::findOrFail(session('admin_id'));

    if (!Hash::check($request->current_password, $admin->admin_password)) {
        return back()->with('error', 'Current password is incorrect.');
    }

    $admin->admin_password = Hash::make($request->new_password);
    $admin->save();

    return back()->with('success', 'Password updated successfully.');
}

public function showReport(Request $request)
{
    $from = $request->input('from');
    $to = $request->input('to');

    if (!$from || !$to) {
        return view('admin.reports', [
            'from' => null,
            'to' => null,
            'totalSales' => 0,
            'totalBookings' => 0,
            'dailySalesLabels' => [],
            'dailySalesData' => [],
            'serviceNames' => [],
            'serviceCounts' => [],
        ]);
    }

    $fromDate = \Carbon\Carbon::parse($from)->startOfDay();
    $toDate = \Carbon\Carbon::parse($to)->endOfDay();

    $payments = \App\Models\Payment::whereBetween('payment_date', [$fromDate, $toDate])->get();
    $totalSales = $payments->sum('amount');
    $totalBookings = \App\Models\Booking::whereBetween('datetime', [$fromDate, $toDate])->count();

    $daily = \App\Models\Payment::selectRaw("DATE(payment_date) as date, SUM(amount) as total")
        ->whereBetween('payment_date', [$fromDate, $toDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    $dailySalesLabels = $daily->pluck('date');
    $dailySalesData = $daily->pluck('total');

    $bookingServices = \App\Models\BookingService::whereHas('booking', fn($q) =>
        $q->whereBetween('datetime', [$fromDate, $toDate])
    )->get();

    $grouped = $bookingServices->groupBy('service_id');

    $serviceNames = [];
    $serviceCounts = [];

    foreach ($grouped as $sid => $group) {
        $serviceNames[] = \App\Models\Service::find($sid)->name ?? 'Deleted';
        $serviceCounts[] = $group->count();
    }

    return view('admin.reports', compact(
        'from', 'to',
        'totalSales', 'totalBookings',
        'dailySalesLabels', 'dailySalesData',
        'serviceNames', 'serviceCounts'
    ));
}


}
