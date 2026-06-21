<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function index()
    {
        $polls = Poll::withCount('votes')->with('options')->latest()->paginate(20);
        return view('polls.index', compact('polls'));
    }

    public function show(Poll $poll)
    {
        $poll->load('options.votes');
        $userVote = PollVote::where('poll_id', $poll->id)
            ->where('user_id', auth()->id())->first();

        $results = $poll->options->map(function ($option) use ($poll) {
            $total = $poll->votes()->count();
            return [
                'option' => $option,
                'count'  => $option->votes->count(),
                'pct'    => $total > 0 ? round(($option->votes->count() / $total) * 100) : 0,
            ];
        });

        return view('polls.show', compact('poll', 'results', 'userVote'));
    }

    public function vote(Request $request, Poll $poll)
    {
        if (! $poll->isActive()) {
            return back()->withErrors(['poll' => 'This poll is not active.']);
        }

        if (PollVote::where('poll_id', $poll->id)->where('user_id', auth()->id())->exists()) {
            return back()->withErrors(['vote' => 'You have already voted in this poll.']);
        }

        $data = $request->validate([
            'poll_option_id' => 'required|exists:poll_options,id',
        ]);

        PollVote::create([
            'poll_id'       => $poll->id,
            'poll_option_id' => $data['poll_option_id'],
            'user_id'       => auth()->id(),
            'voter_hash'    => hash('sha256', auth()->id() . '_poll_' . $poll->id),
        ]);

        return redirect()->route('polls.show', $poll)
            ->with('success', 'Your poll response has been recorded.');
    }
}
