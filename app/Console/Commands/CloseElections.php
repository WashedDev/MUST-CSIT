<?php

namespace App\Console\Commands;

use App\Models\Election;
use Illuminate\Console\Command;

class CloseElections extends Command
{
    protected $signature = 'elections:close-expired';
    protected $description = 'Auto-close elections that have passed their end date';

    public function handle()
    {
        $closed = Election::where('status', 'active')
            ->where('ends_at', '<=', now())
            ->update(['status' => 'closed']);

        $this->info("Closed {$closed} expired election(s).");

        return 0;
    }
}
