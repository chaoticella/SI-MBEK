<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kandang extends Model
{
    use HasFactory;

    protected $fillable = [
        'blok_kandang',
        'kapasitas_maksimal',
        'kategori',
        'status_aktif',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'kapasitas_maksimal' => 'integer',
            'status_aktif' => 'boolean',
        ];
    }

    /**
     * Relasi Modul 2: satu kandang memiliki banyak kambing
     */
    public function kambings(): HasMany
    {
        return $this->hasMany(Kambing::class);
    }

    /**
     * Scope for active kandangs
     */
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    /**
     * Human-readable label for kategori
     */
    public function getKategoriLabelAttribute(): string
    {
        return match (strtolower($this->kategori ?? '')) {
            'jantan' => 'Kambing Jantan',
            'betina' => 'Kambing Betina',
            'isolasi' => 'Isolasi / Sakit',
            default => ucfirst($this->kategori ?? '-'),
        };
    }

    /**
     * Color badge class for UI
     */
    public function getKategoriBadgeAttribute(): array
    {
        return match (strtolower($this->kategori ?? '')) {
            'jantan' => [
                'bg' => 'bg-blue-50 text-blue-700 border-blue-200',
                'dot' => 'bg-blue-500',
                'text' => 'Jantan',
            ],
            'betina' => [
                'bg' => 'bg-pink-50 text-pink-700 border-pink-200',
                'dot' => 'bg-pink-500',
                'text' => 'Betina',
            ],
            'isolasi' => [
                'bg' => 'bg-red-50 text-red-700 border-red-200',
                'dot' => 'bg-red-500',
                'text' => 'Isolasi / Sakit',
            ],
            default => [
                'bg' => 'bg-gray-50 text-gray-700 border-gray-200',
                'dot' => 'bg-gray-500',
                'text' => ucfirst($this->kategori ?? '-'),
            ],
        };
    }
}