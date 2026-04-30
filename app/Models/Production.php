<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = [
        'product_id',
        'production_date',
        'quantity_produced',
        'status',
    ];

    protected $casts = [
        'production_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function qualityInspections()
    {
        return $this->hasMany(QualityInspection::class);
    }

    public function financeRecords()
    {
        return $this->hasMany(FinanceRecord::class);
    }
}