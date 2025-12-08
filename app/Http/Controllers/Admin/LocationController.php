<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::withCount('books')->latest()->paginate(20);
        return view('admin.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:locations,code|max:10',
            'name' => 'required|max:255',
            'type' => 'required|in:room,shelf,cabinet',
            'description' => 'nullable',
            'capacity' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        Location::create($validated);

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'code' => 'required|max:10|unique:locations,code,' . $location->id,
            'name' => 'required|max:255',
            'type' => 'required|in:room,shelf,cabinet',
            'description' => 'nullable',
            'capacity' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $location->update($validated);

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(Location $location)
    {
        if ($location->books()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus lokasi yang memiliki buku.');
        }

        $location->delete();
        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil dihapus.');
    }
}
