<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::orderBy('valid_until', 'desc')->get();
        return view('admin.manage-discounts', compact('discounts'));
    }

    public function create()
    {
        return view('admin.add-discount');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:discounts,code',
            'percentage' => 'required|numeric|min:1|max:100',
            'valid_until' => 'required|date|after:today',
        ]);

        Discount::create([
            'code' => strtoupper($request->code),
            'percentage' => $request->percentage,
            'valid_until' => $request->valid_until,
        ]);

        return redirect()->route('admin.manage-discount')->with('success', 'Discount added successfully.');
    }

    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        return view('admin.edit-discount', compact('discount'));
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:discounts,code,' . $discount->discount_id . ',discount_id',
            'percentage' => 'required|numeric|min:1|max:100',
            'valid_until' => 'required|date|after:today',
        ]);

        $discount->update([
            'code' => strtoupper($request->code),
            'percentage' => $request->percentage,
            'valid_until' => $request->valid_until,
        ]);

        return redirect()->route('admin.manage-discount')->with('success', 'Discount updated successfully.');
    }

    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();

        return redirect()->route('admin.manage-discount')->with('success', 'Discount deleted successfully.');
    }
}
