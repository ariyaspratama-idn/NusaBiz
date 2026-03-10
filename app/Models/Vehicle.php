<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'license_plate',
        'brand',
        'model',
        'year',
        'color',
        'vin_number',
        'current_odometer',
        'last_oil_change_km',
        'last_oil_change_date',
        'oil_change_interval_months',
        'next_oil_change_date',
        'first_reminder_sent_at',
        'second_reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'last_oil_change_date' => 'date',
            'next_oil_change_date' => 'date',
            'first_reminder_sent_at' => 'datetime',
            'second_reminder_sent_at' => 'datetime',
        ];
    }

    /**
     * Get the owner of the vehicle
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bookings for this vehicle
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the service histories for this vehicle
     */
    public function serviceHistories()
    {
        return $this->hasMany(ServiceHistory::class);
    }

    /**
     * Check if oil change is overdue
     */
    public function isOilChangeOverdue(): bool
    {
        if (!$this->next_oil_change_date) {
            return false;
        }

        return now()->greaterThan($this->next_oil_change_date);
    }

    /**
     * Get days overdue for oil change
     */
    public function daysOverdue(): int
    {
        if (!$this->isOilChangeOverdue()) {
            return 0;
        }

        return now()->diffInDays($this->next_oil_change_date);
    }

    /**
     * Get oil change status
     */
    public function getOilChangeStatus(): string
    {
        if (!$this->next_oil_change_date) {
            return 'unknown';
        }

        $daysOverdue = $this->daysOverdue();

        if ($daysOverdue >= 30) {
            return 'urgent'; // 1 month overdue
        } elseif ($daysOverdue >= 7) {
            return 'warning'; // 1 week overdue
        }

        return 'ok';
    }
    /**
     * Check if vehicle needs oil change (alias for isOilChangeOverdue for view compatibility)
     */
    public function needsOilChange(): bool
    {
        return $this->isOilChangeOverdue();
    }
}
