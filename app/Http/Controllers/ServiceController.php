<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    $services = Service::query()
        ->when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })
        ->get();

    return view('admin.manage-services', compact('services'));
}

public function create()
{
    return view('admin.add-service');
}

public function store(Request $request)
{
     $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'duration' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
    ]);

    $path = $request->file('image')->store('services', 'public');

    Service::create([
        'name' => $validated['name'],
        'description' => $validated['description'],
        'price' => $validated['price'],
        'duration' => $validated['duration'],
        'image_url' => 'storage/' . $path, 
    ]);

    return redirect()->route('admin.service')->with('success', 'Service added successfully.');
}

public function edit($id)
{
    $service = Service::findOrFail($id);
    return view('admin.edit-service', compact('service'));
}

public function update(Request $request, $id)
{
    $service = Service::findOrFail($id);

     $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'duration' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
    ]);

    $service->name = $validated['name'];
    $service->description = $validated['description'];
    $service->price = $validated['price'];
    $service->duration = $validated['duration'];

    if ($request->hasFile('image')) {
        if ($service->image_url && Storage::disk('public')->exists(str_replace('storage/', '', $service->image_url))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $service->image_url));
        }

        $path = $request->file('image')->store('services', 'public');
        $service->image_url = 'storage/' . $path;
    }

    $service->save();

    return redirect()->route('admin.service')->with('success', 'Service updated successfully.');
}

public function destroy($id)
{
    Service::destroy($id);
    return redirect()->route('admin.service')->with('success', 'Service deleted.');
}

public function showServices()
{
    $user = Auth::user();

    if (!$user->selected_store_id) {
        return redirect()->route('booking')->with('error', 'Please select a store first.');
    }

    $services = Service::all(); 

    return view('select-service', compact('services'));
}

public function showServicePage()
{
        $services = Service::with([
        'bookingServices.booking.feedback.user'
    ])->get();
    return view('our-service', compact('services'));
}

}
