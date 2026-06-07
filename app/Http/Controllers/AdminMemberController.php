<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminMemberController extends Controller
{
    public function index()
    {
        $members = User::latest()->paginate(20);
        return view('admin.members.index', compact('members'));
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
