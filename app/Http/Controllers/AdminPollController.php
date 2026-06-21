<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Http\Request;

class AdminPollController extends Controller
{
    public function create()
    {
        return view('admin.polls.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question'      => 'required|string|max:255',
            'description'   => 'nullable|string',
            'ends_at'       => 'nullable|date|after:now',
            'options'       => 'required|array|min:2',
            'options.*'     => 'required|string|max:255',
        ]);

        $poll = Poll::create([
            'question'    => $data['question'],
            'description' => $data['description'] ?? null,
            'ends_at'     => $data['ends_at'] ?? null,
            'created_by'  => auth()->id(),
        ]);

        foreach ($data['options'] as $i => $label) {
            PollOption::create([
                'poll_id'  => $poll->id,
                'label'    => $label,
                'position' => $i,
            ]);
        }

        return redirect()->route('polls.show', $poll)
            ->with('success', 'Poll created.');
    }

    public function close(Poll $poll)
    {
        $poll->update(['status' => 'closed']);
        return back()->with('success', 'Poll closed.');
    }
}
