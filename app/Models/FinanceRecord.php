<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceRecord extends Model
{
    protected $fillable = [
        'production_id',
        'cost_type',
        'amount',
        'record_date',
        'recorded_by',
    ];

    protected $casts = [
        'record_date' => 'date',
        'amount'      => 'decimal:2',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}