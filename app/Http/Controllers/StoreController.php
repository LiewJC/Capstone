<?php

namespace App\Http\Controllers;
use App\Models\Store;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{

    public function create()
{
    return view('admin.add-store');
}

public function edit($id)
{
    $store = Store::with('schedules')->findOrFail($id);
    return view('admin.edit-store', compact('store'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string',
        'address' => 'required|string',
        'contact_number' => 'required|string',
        'latitude' => 'required|string',
        'longitude' => 'required|string',
        'operation_hours' => 'required|string',
        'status' => 'required|string',
        'days' => 'required|array',
        'start_time' => 'required|string',
        'end_time' => 'required|string',
    ]);

    $store = Store::findOrFail($id);
    $store->update($request->only([
        'name', 'address', 'contact_number', 'latitude', 'longitude', 'operation_hours', 'status'
    ]));

    Schedule::where('store_id', $id)->delete();

    foreach ($request->days as $day) {
        Schedule::create([
            'store_id' => $id,
            'day_of_week' => $day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
    }

    return redirect()->route('admin.manage-store')->with('success', 'Store updated.');
}

    public function index(Request $request)
    {
        $search = $request->input('search');

        $stores = Store::query();

        if ($search) {
            $stores->where('name', 'like', "%{$search}%")
                   ->orWhere('address', 'like', "%{$search}%");
        }

        $stores = $stores->get();

        return view('admin.manage-stores', compact('stores', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'contact_number' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'operation_hours' => 'required|string',
            'status' => 'required|string',
            'days' => 'required|array',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
        ]);

        $store = Store::create([
            'name' => $request->name,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'operation_hours' => $request->operation_hours,
            'status' => $request->status,
        ]);

        foreach ($request->days as $day) {
            Schedule::create([
                'store_id' => $store->store_id,
                'day_of_week' => $day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]);
        }

        return redirect()->route('admin.manage-store')->with('success', 'Store added successfully.');
    }

    public function destroy($id)
    {
        Store::destroy($id);
        Schedule::where('store_id', $id)->delete();

        return redirect()->route('admin.manage-store')->with('success', 'Store deleted.');
    }

    public function showAllStore()
{
$stores = Store::where('status', 'active')->get();

    $selectedStore = null;
    if (Auth::check() && Auth::user()->selected_store_id) {
        $selectedStore = Store::find(Auth::user()->selected_store_id);
    }

    return view('select-store', compact('stores', 'selectedStore'));}

public function selectStore(Request $request)
{
    $request->validate([
        'store_id' => 'required|exists:stores,store_id',
    ]);

    $user = Auth::user();
    $user->selected_store_id = $request->store_id;
    $user->save();

    return redirect()->back()->with('success', 'Store selected successfully!');
}

}
