<?php

namespace App\Console\Commands;

use App\Mail\ElectionOpened;
use App\Models\Election;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class OpenElections extends Command
{
    protected $signature = 'elections:open-pending';
    protected $description = 'Auto-open elections that have reached their start date';

    public function handle()
    {
        $elections = Election::where('status', 'pending')
            ->where('starts_at', '<=', now())
            ->get();

        foreach ($elections as $election) {
            $election->update(['status' => 'active']);

            $voters = User::where('membership_paid', true)->whereNull('deleted_at')->get();
            foreach ($voters as $voter) {
                Mail::to($voter->email)->queue(new ElectionOpened($election));
            }
        }

        $this->info("Opened {$elections->count()} pending election(s).");

        return 0;
    }
}
