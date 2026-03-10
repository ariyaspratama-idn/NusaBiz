<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'booking_date',
        'status',
        'complaint',
        'notes',
        'estimated_cost',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'datetime',
            'estimated_cost' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function workOrder()
    {
        return $this->hasOne(WorkOrder::class);
    }
}
