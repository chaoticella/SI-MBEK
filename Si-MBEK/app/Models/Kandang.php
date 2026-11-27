<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kandang extends Model
{
    use HasFactory;

    protected $table = 'kandang';
    protected $primaryKey = 'id_kandang';

    public const KATEGORI_ISOLASI = 'isolasi';

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
     * Compatibility alias for id
     * Relasi Modul 2: satu kandang memiliki banyak kambing
     */
    public function kambings(): HasMany
    {
        return $this->hasMany(Kambing::class);
    }

    /**
     * Scope for active kandangs
     */
    public function getIdAttribute()
    {
        return $this->attributes['id_kandang'] ?? null;
    }

    /**
     * Relasi ke data kambing
     */
    public function kambing(): HasMany
    {
        return $this->hasMany(Kambing::class, 'id_kandang', 'id_kandang');
    }

    public function isIsolasi(): bool
    {
        return $this->kategori === self::KATEGORI_ISOLASI;
    }

    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    public function getLabelAttribute(): string
    {
        return 'Blok ' . $this->blok_kandang . ' (' . ucfirst($this->kategori) . ')';
    }

    public function getKategoriLabelAttribute(): string
    {
        return match (strtolower($this->kategori ?? '')) {
            'jantan' => 'Kambing Jantan',
            'betina' => 'Kambing Betina',
            'isolasi' => 'Isolasi / Sakit',
            default => ucfirst($this->kategori ?? '-'),
        };
    }

    public function getKategoriBadgeAttribute(): array
    {
        return match (strtolower($this->kategori ?? '')) {
            'jantan' => [
                'bg' => 'bg-amber-100 text-amber-900 border-amber-200',
                'dot' => 'bg-amber-700',
                'text' => 'Jantan',
            ],
            'betina' => [
                'bg' => 'bg-stone-200 text-stone-800 border-stone-300',
                'dot' => 'bg-[#6B9080]',
                'text' => 'Betina',
            ],
            'isolasi' => [
                'bg' => 'bg-red-50 text-red-700 border-red-200',
                'dot' => 'bg-red-500',
                'text' => 'Isolasi / Sakit',
            ],
            default => [
                'bg' => 'bg-stone-100 text-stone-700 border-stone-200',
                'dot' => 'bg-stone-500',
                'text' => ucfirst($this->kategori ?? '-'),
            ],
        };
    }
}