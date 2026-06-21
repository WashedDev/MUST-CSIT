<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\User;
use Illuminate\Http\Request;

class AdminElectionController extends Controller
{
    public function index()
    {
        $elections = Election::withCount('candidates', 'votes')->latest()->paginate(20);
        return view('admin.elections.index', compact('elections'));
    }

    public function create()
    {
        return view('admin.elections.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'starts_at'      => 'required|date',
            'ends_at'        => 'required|date|after:starts_at',
            'eligible_group' => 'required|in:all,member,moderator,executive,admin',
            'election_type'  => 'required|in:single,multiple',
        ]);

        $data['status'] = 'pending';

        Election::create($data);

        return redirect()->route('admin.elections.index')
            ->with('success', 'Election created.');
    }

    public function edit(Election $election)
    {
        $election->load('candidates.user');
        $members = User::where('membership_paid', true)
            ->whereDoesntHave('candidates', fn($q) => $q->where('election_id', $election->id))
            ->orderBy('firstname')
            ->get();
        return view('admin.elections.edit', compact('election', 'members'));
    }

    public function addCandidate(Request $request, Election $election)
    {
        $data = $request->validate([
            'user_id'  => 'required|exists:users,id',
            'position' => 'required|string|max:255',
            'manifesto' => 'nullable|string',
        ]);

        $data['election_id'] = $election->id;

        Candidate::create($data);

        return redirect()->route('admin.elections.edit', $election)
            ->with('success', 'Candidate added.');
    }

    public function removeCandidate(Election $election, Candidate $candidate)
    {
        if ($candidate->election_id !== $election->id) {
            return back()->withErrors(['candidate' => 'Candidate does not belong to this election.']);
        }

        $candidate->delete();

        return redirect()->route('admin.elections.edit', $election)
            ->with('success', 'Candidate removed.');
    }

    public function update(Request $request, Election $election)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'starts_at'      => 'required|date',
            'ends_at'        => 'required|date|after:starts_at',
            'eligible_group' => 'required|in:all,member,moderator,executive,admin',
            'election_type'  => 'required|in:single,multiple',
        ]);

        $election->update($data);

        return redirect()->route('admin.elections.index')
            ->with('success', 'Election updated.');
    }

    public function updateStatus(Request $request, Election $election)
    {
        $data = $request->validate([
            'status' => 'required|in:active,paused,closed',
        ]);

        $election->update(['status' => $data['status']]);

        return redirect()->route('admin.elections.index')
            ->with('success', 'Election status updated to ' . $data['status'] . '.');
    }

    public function destroy(Election $election)
    {
        $election->candidates()->delete();
        $election->votes()->delete();
        $election->delete();

        return redirect()->route('admin.elections.index')
            ->with('success', 'Election deleted.');
    }
}
