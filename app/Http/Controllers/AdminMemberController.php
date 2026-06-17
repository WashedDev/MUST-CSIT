<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminMemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $members = User::when($search, fn($q, $v) => $q->where(function ($q) use ($v) {
                $q->where('firstname', 'like', "%{$v}%")
                  ->orWhere('lastname', 'like', "%{$v}%")
                  ->orWhere('email', 'like', "%{$v}%")
                  ->orWhere('reg_number', 'like', "%{$v}%")
                  ->orWhere('programme', 'like', "%{$v}%");
            }))
            ->latest()
            ->paginate(20);

        return view('admin.members.index', compact('members', 'search'));
    }

    public function edit(User $member)
    {
        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, User $member)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $member->id,
            'reg_number'=> 'nullable|string|max:50',
            'programme' => 'nullable|string|max:255',
            'year'      => 'nullable|string|max:10',
            'role'              => 'required|in:member,moderator,executive,admin',
            'membership_status' => 'required|in:active,suspended,expired,alumni',
        ]);

        $member->update($data);

        return redirect()->route('admin.members.index')
            ->with('success', 'Member updated.');
    }

    public function destroy(User $member)
    {
        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Member removed.');
    }
}
