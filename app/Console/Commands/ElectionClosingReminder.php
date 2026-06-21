<?php

namespace App\Console\Commands;

use App\Mail\ElectionClosingReminder as ElectionClosingReminderMailable;
use App\Models\Election;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ElectionClosingReminder extends Command
{
    protected $signature = 'elections:send-closing-reminders';
    protected $description = 'Send email reminders for elections closing within 24 hours';

    public function handle()
    {
        $elections = Election::where('status', 'active')
            ->where('ends_at', '>', now())
            ->where('ends_at', '<=', now()->addHours(24))
            ->get();

        foreach ($elections as $election) {
            $voters = User::where('membership_paid', true)
                ->whereNull('deleted_at')
                ->get();

            foreach ($voters as $voter) {
                Mail::to($voter->email)->queue(new ElectionClosingReminderMailable($election));
            }

            $this->info("Sent reminders for: {$election->title}");
        }

        if ($elections->isEmpty()) {
            $this->info('No elections closing within 24 hours.');
        }

        return 0;
    }
}
