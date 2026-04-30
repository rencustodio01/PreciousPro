<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'role_id',
        'contact_number',
        'birthday',
        'birthplace',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password'       => 'hashed',
            'birthday'       => 'date',
            'contact_number' => 'encrypted',
            'birthplace'     => 'encrypted',
        ];
    }

    // ── Relationships ────────────────────────────────────
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // ── Role Helpers ─────────────────────────────────────
    public function hasRole(string $role): bool
    {
        return $this->role?->role_name === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role?->role_name, $roles);
    }
}