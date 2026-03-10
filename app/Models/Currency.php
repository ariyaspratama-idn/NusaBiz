<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class Currency extends Model
{
    use Auditable;

    protected $fillable = [
        'code', 'name', 'symbol', 'exchange_rate', 'is_default'
    ];
}
