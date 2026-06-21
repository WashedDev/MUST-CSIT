<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('events:send-reminders')->hourly();
Schedule::command('elections:open-pending')->everyMinute();
Schedule::command('elections:close-expired')->everyMinute();
Schedule::command('articles:publish-scheduled')->everyMinute();
Schedule::command('elections:send-closing-reminders')->hourly();
