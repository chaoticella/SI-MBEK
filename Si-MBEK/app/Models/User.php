<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status_aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status_aktif' => 'boolean',
        ];
    }

    /**
     * Check if user is pengelola (or pemilik/admin)
     */
    public function isPengelola(): bool
    {
        return in_array(strtolower($this->role ?? ''), ['pengelola', 'pemilik', 'admin']);
    }

    /**
     * Check if user is pegawai
     */
    public function isPegawai(): bool
    {
        return strtolower($this->role ?? '') === 'pegawai';
    }

    /**
     * Human-readable role label
     */
    public function getRoleLabelAttribute(): string
    {
        return match (strtolower($this->role ?? '')) {
            'pengelola', 'pemilik', 'admin' => 'Pengelola',
            'pegawai' => 'Pegawai',
            default => ucfirst($this->role ?? '-'),
        };
    }
}
