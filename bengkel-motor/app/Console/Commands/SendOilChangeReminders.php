<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendOilChangeReminders extends Command
{
    protected $signature = 'reminders:oil-change';
    protected $description = 'Send oil change reminders to customers';

    public function handle()
    {
        $this->info('Checking for vehicles needing oil change reminders...');

        $reminderDays = (int) \App\Models\Setting::get('oil_change_reminder_days', 7);
        $reminderDate = now()->addDays($reminderDays);

        // Find vehicles needing first reminder (7 days before due date)
        $vehiclesForFirstReminder = Vehicle::whereNotNull('next_oil_change_date')
            ->whereDate('next_oil_change_date', '<=', $reminderDate)
            ->whereNull('first_reminder_sent_at')
            ->with('user')
            ->get();

        foreach ($vehiclesForFirstReminder as $vehicle) {
            $this->sendReminder($vehicle, 'first');
            $vehicle->update(['first_reminder_sent_at' => now()]);
        }

        // Find vehicles needing second reminder (overdue)
        $vehiclesForSecondReminder = Vehicle::whereNotNull('next_oil_change_date')
            ->whereDate('next_oil_change_date', '<', now())
            ->whereNull('second_reminder_sent_at')
            ->whereNotNull('first_reminder_sent_at')
            ->with('user')
            ->get();

        foreach ($vehiclesForSecondReminder as $vehicle) {
            $this->sendReminder($vehicle, 'second');
            $vehicle->update(['second_reminder_sent_at' => now()]);
        }

        $totalReminders = $vehiclesForFirstReminder->count() + $vehiclesForSecondReminder->count();
        $this->info("Sent {$totalReminders} oil change reminders.");

        Log::info("Oil change reminders sent", [
            'first_reminders' => $vehiclesForFirstReminder->count(),
            'second_reminders' => $vehiclesForSecondReminder->count(),
        ]);

        return 0;
    }

    private function sendReminder(Vehicle $vehicle, string $type)
    {
        $customer = $vehicle->user;
        
        // In a real application, you would send email/SMS here
        // For now, we'll just log it
        
        $message = $type === 'first' 
            ? "Reminder: Your {$vehicle->brand} {$vehicle->model} ({$vehicle->license_plate}) is due for oil change on {$vehicle->next_oil_change_date->format('d M Y')}"
            : "URGENT: Your {$vehicle->brand} {$vehicle->model} ({$vehicle->license_plate}) oil change is OVERDUE since {$vehicle->next_oil_change_date->format('d M Y')}";

        Log::info("Oil change reminder", [
            'type' => $type,
            'customer' => $customer->name,
            'email' => $customer->email,
            'vehicle' => $vehicle->license_plate,
            'due_date' => $vehicle->next_oil_change_date,
            'message' => $message,
        ]);

        // TODO: Implement actual notification
        // Mail::to($customer->email)->send(new OilChangeReminderMail($vehicle, $type));
        // Or SMS notification
        
        $this->line("  → {$type} reminder sent to {$customer->name} for {$vehicle->license_plate}");
    }
}
