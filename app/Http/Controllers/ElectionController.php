<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Vote;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::latest()->get();
        return view('elections.index', compact('elections'));
    }

    public function show(Election $election)
    {
        $candidates = $election->candidates()->with('user')->get();
        $userVote = Vote::where('election_id', $election->id)
            ->where('user_id', auth()->id())->first();

        return view('elections.show', compact('election', 'candidates', 'userVote'));
    }

    public function vote(Request $request, Election $election)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        if (! $election->isActive()) {
            return back()->withErrors(['election' => 'This election is not currently active.']);
        }

        if (Vote::where('election_id', $election->id)->where('user_id', auth()->id())->exists()) {
            return back()->withErrors(['vote' => 'You have already voted in this election.']);
        }

        Vote::create([
            'election_id'  => $election->id,
            'user_id'      => auth()->id(),
            'candidate_id' => $request->candidate_id,
        ]);

        return redirect()->route('elections.show', $election)
            ->with('success', 'Your vote has been recorded.');
    }

    public function results(Election $election)
    {
        $results = Candidate::where('election_id', $election->id)
            ->withCount('votes')
            ->with('user')
            ->orderByDesc('votes_count')
            ->get();

        $totalVotes = $results->sum('votes_count');

        return view('elections.results', compact('election', 'results', 'totalVotes'));
    }
}
