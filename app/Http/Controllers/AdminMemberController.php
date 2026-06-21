<?php

namespace App\Http\Controllers;

use App\Mail\MembershipApproved;
use App\Mail\MembershipRejected;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
        if ($member->id === auth()->id()) {
            return back()->withErrors(['role' => 'You cannot edit your own account via this page.']);
        }

        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $member->id,
            'reg_number'=> 'nullable|string|max:50',
            'programme' => 'nullable|string|max:255',
            'year'      => 'nullable|string|max:10',
            'role'              => 'required|in:member,moderator,executive,admin',
            'membership_status' => 'required|in:active,suspended,expired,alumni',
            'permissions'       => 'nullable|array',
            'permissions.*'     => 'in:' . implode(',', User::availablePermissions()),
        ]);

        $data['permissions'] = $request->permissions ?? [];

        $member->forceFill($data)->save();

        return redirect()->route('admin.members.index')
            ->with('success', 'Member updated.');
    }

    public function pending()
    {
        $members = User::where('membership_paid', true)
            ->where('approved', false)
            ->where('membership_status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.members.pending', compact('members'));
    }

    public function approve(User $member)
    {
        $member->forceFill([
            'approved'          => true,
            'approved_at'       => now(),
            'membership_status' => 'active',
        ])->save();

        Mail::to($member->email)->queue(new MembershipApproved($member));

        return redirect()->route('admin.members.pending')
            ->with('success', "{$member->name} approved.");
    }

    public function reject(Request $request, User $member)
    {
        $data = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $member->forceFill([
            'membership_status' => 'rejected',
        ])->save();

        Mail::to($member->email)->queue(new MembershipRejected($member, $data['reason'] ?? null));

        return redirect()->route('admin.members.pending')
            ->with('success', "{$member->name} rejected.");
    }

    public function importForm()
    {
        return view('admin.members.import');
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $path = $request->file('csv')->getRealPath();
        $handle = fopen($path, 'r');
        $imported = 0;
        $errors = [];

        $header = fgetcsv($handle);
        if (! $header || count($header) < 3) {
            fclose($handle);
            return back()->withErrors(['csv' => 'CSV must have at least 3 columns: firstname, lastname, email, [reg_number], [programme], [year]']);
        }

        $header = array_map('trim', $header);

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, array_map('trim', $row));

            if (empty($data['firstname']) || empty($data['lastname']) || empty($data['email'])) {
                continue;
            }

            if (User::where('email', $data['email'])->exists()) {
                $errors[] = "Skipped {$data['email']} — already exists.";
                continue;
            }

            try {
                $user = User::create([
                    'firstname'    => $data['firstname'],
                    'lastname'     => $data['lastname'],
                    'email'        => $data['email'],
                    'reg_number'   => $data['reg_number'] ?? null,
                    'programme'    => $data['programme'] ?? null,
                    'year'         => $data['year'] ?? null,
                ]);
                $user->forceFill([
                    'password'          => Hash::make('csit2026'),
                    'role'              => 'member',
                    'membership_paid'   => true,
                    'membership_status' => 'active',
                    'approved'          => true,
                    'approved_at'       => now(),
                    'paid_at'           => now(),
                ])->save();
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Failed to import {$data['email']}: {$e->getMessage()}";
            }
        }

        fclose($handle);

        $msg = "Imported {$imported} member(s).";
        if ($errors) {
            $msg .= ' ' . implode(' ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $msg .= ' And ' . (count($errors) - 5) . ' more errors.';
            }
        }

        return redirect()->route('admin.members.index')
            ->with('success', $msg);
    }

    public function destroy(User $member)
    {
        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Member removed.');
    }
}
