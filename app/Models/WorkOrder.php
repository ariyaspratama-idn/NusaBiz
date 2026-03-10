<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'mechanic_id',
        'status',
        'started_at',
        'completed_at',
        'total_cost',
        'diagnosis',
        'work_done',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'total_cost' => 'decimal:2',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function items()
    {
        return $this->hasMany(WorkOrderItem::class);
    }

    public function progress()
    {
        return $this->hasMany(WorkOrderProgress::class);
    }

    public function serviceHistory()
    {
        return $this->hasOne(ServiceHistory::class);
    }
}
