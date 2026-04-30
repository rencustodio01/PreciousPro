<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityInspection extends Model
{
    protected $fillable = [
        'production_id',
        'inspector_id',
        'inspection_date',
        'result',
        'remarks',
    ];

    protected $casts = [
        'inspection_date' => 'date',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }
}