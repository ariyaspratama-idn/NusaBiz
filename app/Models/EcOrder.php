<?php

namespace App\Models;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EcOrder extends Model
{
    use BelongsToTenant;

    protected $table = 'ec_orders';

    protected $fillable = [
        'tenant_id', 'order_number', 'user_id',
        'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'shipping_district', 'shipping_city',
        'shipping_province', 'shipping_postal_code',
        'shipping_type', 'shipping_courier', 'shipping_service',
        'shipping_cost', 'tracking_number',
        'payment_method', 'payment_token', 'payment_transaction_id',
        'payment_status', 'paid_at', 'payment_proof',
        'status', 'subtotal', 'discount', 'total', 'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
    ];

    public static function generateOrderNumber(): string
    {
        $prefix = 'NB-' . date('Ymd') . '-';
        do {
            $code = $prefix . strtoupper(Str::random(5));
        } while (self::where('order_number', $code)->exists());
        return $code;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(EcOrderItem::class, 'order_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(EcOrderStatusHistory::class, 'order_id');
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }
}
