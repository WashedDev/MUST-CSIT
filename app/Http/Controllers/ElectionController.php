<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\User;
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

    public function confirmVote(Request $request, Election $election)
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

        $candidate = Candidate::with('user')->findOrFail($request->candidate_id);

        return view('elections.confirm', compact('election', 'candidate'));
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
        if ($election->status === 'pending' || $election->isActive()) {
            if ($election->ends_at && now()->greaterThan($election->ends_at) && $election->status === 'active') {
                $election->update(['status' => 'closed']);
            } else {
                return redirect()->route('elections.show', $election)
                    ->with('info', 'Results will be published once the election closes.');
            }
        }

        $results = Candidate::where('election_id', $election->id)
            ->withCount('votes')
            ->with('user')
            ->orderByDesc('votes_count')
            ->get();

        $totalVotes = $results->sum('votes_count');
        $totalEligible = User::where('membership_paid', true)->count();

        return view('elections.results', compact('election', 'results', 'totalVotes', 'totalEligible'));
    }

    public function exportCsv(Election $election)
    {
        if ($election->status !== 'closed') {
            return back()->withErrors(['election' => 'Results can only be exported after the election closes.']);
        }

        $results = Candidate::where('election_id', $election->id)
            ->withCount('votes')
            ->with('user')
            ->orderByDesc('votes_count')
            ->get();

        $totalVotes = $results->sum('votes_count');

        $csv = "Position,Candidate,Votes,Percentage\n";
        foreach ($results as $candidate) {
            $pct = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0;
            $csv .= '"' . $candidate->position . '","' . $candidate->user->name . '",' . $candidate->votes_count . ',' . $pct . "\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="results-' . $election->id . '.csv"',
        ]);
    }
}
