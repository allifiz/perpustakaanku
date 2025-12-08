<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index()
    {
        $publishers = Publisher::latest()->paginate(20);
        return view('admin.publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|max:20',
            'address' => 'nullable',
            'website' => 'nullable|url',
            'country' => 'required|max:100',
        ]);

        $validated['is_active'] = $request->has('is_active');
        Publisher::create($validated);

        return redirect()->route('admin.penerbit.index')
            ->with('success', 'Penerbit berhasil ditambahkan.');
    }

    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|max:20',
            'address' => 'nullable',
            'website' => 'nullable|url',
            'country' => 'required|max:100',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $publisher->update($validated);

        return redirect()->route('admin.penerbit.index')
            ->with('success', 'Penerbit berhasil diperbarui.');
    }

    public function destroy(Publisher $publisher)
    {
        if ($publisher->books()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus penerbit yang memiliki buku.');
        }

        $publisher->delete();
        return redirect()->route('admin.penerbit.index')
            ->with('success', 'Penerbit berhasil dihapus.');
    }
}
