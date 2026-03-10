<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'work_order_id',
        'service_date',
        'odometer_reading',
        'oil_changed',
        'oil_change_km',
        'oil_change_date',
        'next_oil_change_km',
        'next_oil_change_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'service_date' => 'datetime',
            'oil_changed' => 'boolean',
            'oil_change_date' => 'date',
            'next_oil_change_date' => 'date',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}
