<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $listings = Listing::with('farmer')->where('status', 'active')->latest()->get();
        return view('listings.index', compact('listings'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'farmer') abort(403);
        return view('listings.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'farmer') abort(403);

        $validated = $request->validate([
            'crop_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:1',
            'unit' => 'required|string',
            'expected_price' => 'required|numeric|min:0',
            'harvest_date' => 'nullable|date',
        ]);

        $validated['farmer_id'] = auth()->id();
        $validated['status'] = 'active';

        Listing::create($validated);

        return redirect()->route('dashboard')->with('success', 'Listing created successfully.');
    }

    public function show(Listing $listing)
    {
        $listing->load('farmer');
        return view('listings.show', compact('listing'));
    }

    public function edit(Listing $listing)
    {
        if (auth()->id() !== $listing->farmer_id) abort(403);
        return view('listings.edit', compact('listing'));
    }

    public function update(Request $request, Listing $listing)
    {
        if (auth()->id() !== $listing->farmer_id) abort(403);

        $validated = $request->validate([
            'crop_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:1',
            'unit' => 'required|string',
            'expected_price' => 'required|numeric|min:0',
            'harvest_date' => 'nullable|date',
            'status' => 'required|in:active,contracted',
        ]);

        $listing->update($validated);

        return redirect()->route('dashboard')->with('success', 'Listing updated successfully.');
    }

    public function destroy(Listing $listing)
    {
        if (auth()->id() !== $listing->farmer_id) abort(403);
        $listing->delete();
        return redirect()->route('dashboard')->with('success', 'Listing deleted.');
    }
}
