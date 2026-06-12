<?php

namespace App\Console\Commands;

use App\Mail\EventReminder as EventReminderMail;
use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders';
    protected $description = 'Send email and in-app reminders for events starting in ~24 hours';

    public function handle(): int
    {
        $target = now()->addHours(24);

        $bookings = Booking::whereHas('event', function ($q) use ($target) {
            $q->whereDate('date', $target->toDateString())
              ->whereTime('date', '>=', $target->copy()->subHours(2)->format('H:i:s'))
              ->whereTime('date', '<=', $target->copy()->addHours(2)->format('H:i:s'));
        })->whereNull('reminder_sent_at')
          ->whereIn('status', ['confirmed', 'pending_payment'])
          ->with(['event', 'user'])
          ->get();

        if ($bookings->isEmpty()) {
            $this->info('No reminders to send.');
            return Command::SUCCESS;
        }

        $sent = 0;

        foreach ($bookings as $booking) {
            $event = $booking->event;

            // In-app notification
            Notification::notify(
                $booking->user_id,
                'event_reminder',
                'Event Reminder: ' . $event->title,
                'Your event "' . $event->title . '" starts at ' . $event->date->format('M d, Y H:i') . ' at ' . $event->location,
                route('events.index')
            );

            // Email notification
            try {
                Mail::to($booking->user->email)
                    ->send(new EventReminderMail($booking));
            } catch (\Exception $e) {
                $this->warn("Failed to send email to {$booking->user->email}: {$e->getMessage()}");
            }

            $booking->update(['reminder_sent_at' => now()]);
            $sent++;
        }

        $this->info("Sent {$sent} event reminder(s).");
        return Command::SUCCESS;
    }
}
