<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = User::where('role', 'member')->latest()->paginate(10);
        return view('admin.members.index', compact('members'));
    }

    public function show(User $member)
    {
        return view('admin.members.show', compact('member'));
    }

    public function updateStatus(Request $request, User $member)
    {
        $request->validate([
            'status' => 'required|in:active,rejected',
        ]);

        $member->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Member status updated successfully.');
    }

    public function destroy(User $member)
    {
        $member->delete();
        return redirect()->route('admin.members.index')->with('success', 'Member deleted successfully.');
    }
}