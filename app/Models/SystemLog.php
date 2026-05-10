<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $table = 'system_logs';

    protected $fillable = [
        'user_id',
        'user_email',
        'user_name',
        'user_role',
        'role_name',
        'action',
        'model',
        'description',
        'ip_address',
        'user_agent',
        'meta',
        'route_name',
        'method',
        'url',
        'payload',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
