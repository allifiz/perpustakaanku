<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'id_card' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $idCardPath = null;
        if ($request->hasFile('id_card')) {
            $idCardPath = $request->file('id_card')->store('id_cards', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'id_card' => $idCardPath,
            'role' => 'member',
            'status' => 'pending',
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please wait for admin approval.');
    }
}