<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'movement_no', 'movement_date', 'branch_id', 'product_id', 
        'type', 'quantity', 'unit_price', 'total_value', 
        'reference_type', 'reference_id', 'journal_header_id'
    ];

    protected $casts = [
        'movement_date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
