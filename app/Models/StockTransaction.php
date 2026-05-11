<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'inventory_id',
        'transaction_type',
        'quantity',
        'transaction_date',
        'processed_by',
        'location_from',
        'location_to',
        'coordinates_from',
        'coordinates_to',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
