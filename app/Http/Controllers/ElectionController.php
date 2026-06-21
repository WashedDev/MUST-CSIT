<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ElectionController extends Controller
{
    private const VOTER_SALT = 'csit2026';
    private const BALLOT_SALT = 'csit2026_ballot';
    public function index()
    {
        $elections = Election::latest()->get();
        return view('elections.index', compact('elections'));
    }

    public function show(Election $election)
    {
        $candidates = $election->candidates()->with('user')->get();
        $userVote = Vote::where('election_id', $election->id)
            ->where(function ($q) {
                $q->where('user_id', auth()->id())
                  ->orWhere('voter_hash', hash('sha256', auth()->id() . '_' . request()->route('election')->id . '_' . self::VOTER_SALT));
            })->first();

        $userVotes = collect();
        if ($election->election_type === 'multiple') {
            $userVotes = Vote::where('election_id', $election->id)
                ->where(function ($q) {
                    $q->where('user_id', auth()->id())
                      ->orWhere('voter_hash', hash('sha256', auth()->id() . '_' . request()->route('election')->id . '_' . self::VOTER_SALT));
                })->get();
        }

        return view('elections.show', compact('election', 'candidates', 'userVote', 'userVotes'));
    }

    public function confirmVote(Request $request, Election $election)
    {
        if (! $election->isActive()) {
            return back()->withErrors(['election' => 'This election is not currently active.']);
        }

        $hasVoted = Vote::where('election_id', $election->id)
            ->where(function ($q) {
                $q->where('user_id', auth()->id())
                  ->orWhere('voter_hash', hash('sha256', auth()->id() . '_' . request()->route('election')->id . '_' . self::VOTER_SALT));
            })->exists();

        if ($hasVoted) {
            return back()->withErrors(['vote' => 'You have already voted in this election.']);
        }

        if ($election->election_type === 'multiple') {
            $request->validate([
                'candidate_ids' => 'required|array|min:1',
                'candidate_ids.*' => 'exists:candidates,id',
            ]);
            $candidates = Candidate::with('user')->whereIn('id', $request->candidate_ids)->get();
            return view('elections.confirm', compact('election', 'candidates'));
        }

        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        $candidate = Candidate::with('user')->findOrFail($request->candidate_id);
        $candidates = collect([$candidate]);

        return view('elections.confirm', compact('election', 'candidates'));
    }

    public function vote(Request $request, Election $election)
    {
        if (! $election->isActive()) {
            return back()->withErrors(['election' => 'This election is not currently active.']);
        }

        $hasVoted = Vote::where('election_id', $election->id)
            ->where(function ($q) {
                $q->where('user_id', auth()->id())
                  ->orWhere('voter_hash', hash('sha256', auth()->id() . '_' . $election->id . '_' . self::VOTER_SALT));
            })->exists();

        if ($hasVoted) {
            return back()->withErrors(['vote' => 'You have already voted in this election.']);
        }

        $voterHash = hash('sha256', auth()->id() . '_' . $election->id . '_' . self::VOTER_SALT);

        $receiptParts = [];
        $receiptIds = [];

        if ($election->election_type === 'multiple') {
            $request->validate([
                'candidate_ids' => 'required|array|min:1',
                'candidate_ids.*' => 'exists:candidates,id',
            ]);

            foreach ($request->candidate_ids as $candidateId) {
                $receiptHash = hash('sha256', auth()->id() . '|' . $election->id . '|' . $candidateId . '|' . self::BALLOT_SALT);
                $vote = Vote::create([
                    'election_id'  => $election->id,
                    'candidate_id' => $candidateId,
                    'voter_hash'   => $voterHash,
                    'receipt_hash' => $receiptHash,
                    'anonymised'   => true,
                ]);
                $vote->update(['user_id' => null]);
                $receiptParts[] = $receiptHash;
                $receiptIds[] = $vote->id;
            }

            $masterReceipt = hash('sha256', implode('|', $receiptParts) . '_' . self::VOTER_SALT);
            session(['vote_receipt_' . $election->id => $masterReceipt]);

            AuditLog::record('voted', $election, null, [
                'candidate_ids' => $request->candidate_ids,
                'count'         => count($request->candidate_ids),
                'receipt'       => $masterReceipt,
            ]);

            return redirect()->route('elections.receipt', [$election, 'receipt' => $masterReceipt])
                ->with('success', 'Your votes have been recorded.');
        }

        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        $receiptHash = hash('sha256', auth()->id() . '|' . $election->id . '|' . $request->candidate_id . '|' . self::BALLOT_SALT);
        $vote = Vote::create([
            'election_id'  => $election->id,
            'candidate_id' => $request->candidate_id,
            'voter_hash'   => $voterHash,
            'receipt_hash' => $receiptHash,
            'anonymised'   => true,
        ]);
        $vote->update(['user_id' => null]);

        session(['vote_receipt_' . $election->id => $receiptHash]);

        AuditLog::record('voted', $election, null, [
            'vote_id'      => $vote->id,
            'candidate_id' => $request->candidate_id,
            'receipt'      => $receiptHash,
        ]);

        return redirect()->route('elections.receipt', [$election, 'receipt' => $receiptHash])
            ->with('success', 'Your vote has been recorded.');
    }

    public function receipt(Election $election, $receipt)
    {
        $votes = Vote::where('election_id', $election->id)
            ->where('receipt_hash', $receipt)
            ->with('candidate.user')
            ->get();

        if ($votes->isEmpty()) {
            $candidates = Candidate::where('election_id', $election->id)
                ->whereIn('id', function ($q) use ($election, $receipt) {
                    $q->select('candidate_id')->from('votes')
                        ->where('election_id', $election->id)
                        ->where('receipt_hash', $receipt);
                })->with('user')->get();
            if ($candidates->isEmpty()) {
                return redirect()->route('elections.show', $election)
                    ->with('error', 'Invalid receipt for this election.');
            }
        } else {
            $candidates = $votes->pluck('candidate');
        }

        return view('elections.receipt', compact('election', 'receipt', 'votes', 'candidates'));
    }

    public function showVerifyForm(Election $election)
    {
        return view('elections.verify', compact('election'));
    }

    public function verify(Request $request, Election $election)
    {
        $data = $request->validate([
            'receipt' => 'required|string|size:64',
        ]);

        $votes = Vote::where('election_id', $election->id)
            ->where('receipt_hash', $data['receipt'])
            ->with('candidate.user')
            ->get();

        $verified = $votes->isNotEmpty();

        return view('elections.verify', compact('election', 'verified', 'votes'));
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

        $totalVotes = Vote::where('election_id', $election->id)->count();
        $uniqueVoters = Vote::where('election_id', $election->id)
            ->distinct('voter_hash')
            ->count('voter_hash');
        $totalEligible = User::where('membership_paid', true)->count();

        return view('elections.results', compact('election', 'results', 'totalVotes', 'uniqueVoters', 'totalEligible'));
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
