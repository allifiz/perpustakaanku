<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        return view('member.profile.show');
    }

    public function edit()
    {
        return view('member.profile.edit');
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'id_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'phone', 'address']);

        if ($request->hasFile('id_card')) {
            if ($user->id_card) {
                Storage::disk('public')->delete($user->id_card);
            }
            $data['id_card'] = $request->file('id_card')->store('id_cards', 'public');
        }

        // Gunakan updateOrFail atau save untuk lebih aman
        $user->fill($data);
        $user->save();

        return redirect()->route('member.profile.show')->with('success', 'Profile updated successfully.');
    }
}