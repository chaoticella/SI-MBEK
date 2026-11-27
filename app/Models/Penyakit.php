<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penyakit extends Model
{
    protected $table = 'penyakit';
    protected $primaryKey = 'id_penyakit';

    public const STATUS = ['sakit' => 'Masih Sakit', 'sembuh' => 'Sembuh', 'mati' => 'Mati'];

    protected $fillable = [
        'id_kambing', 'id_user', 'tanggal_sakit', 'diagnosa', 'gejala',
        'jenis_obat_vaksin', 'tanggal_sembuh', 'tanggal_kematian', 'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_sakit'     => 'date',
            'tanggal_sembuh'    => 'date',
            'tanggal_kematian'  => 'date',
            'jenis_obat_vaksin' => 'array',
        ];
    }

    public function kambing(): BelongsTo
    {
        return $this->belongsTo(Kambing::class, 'id_kambing', 'id_kambing');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /* ---------- Status ---------- */

    public function getStatusAttribute(): string
    {
        if ($this->tanggal_kematian) return 'mati';
        if ($this->tanggal_sembuh)   return 'sembuh';
        return 'sakit';
    }

    public function getLamaSakitAttribute(): int
    {
        $akhir = $this->tanggal_kematian ?? $this->tanggal_sembuh ?? now();
        return (int) $this->tanggal_sakit->diffInDays($akhir);
    }

    /** Apakah kambingnya sedang berada di kandang isolasi. */
    public function diIsolasi(): bool
    {
        return $this->kambing?->kandang?->kategori === Kandang::KATEGORI_ISOLASI;
    }

    /* ---------- Scopes ---------- */

    /** Kasus yang masih berjalan (belum sembuh & belum mati). */
    public function scopeAktif(Builder $q): Builder
    {
        return $q->whereNull('tanggal_sembuh')->whereNull('tanggal_kematian');
    }

    public function scopeDiKandangIsolasi(Builder $q): Builder
    {
        return $q->whereHas('kambing.kandang',
            fn ($k) => $k->where('kategori', Kandang::KATEGORI_ISOLASI));
    }

    public function scopeFilter(Builder $q, array $f): Builder
    {
        return $q
            ->when($f['kambing'] ?? null, fn ($q, $v) => $q->where('id_kambing', $v))
            ->when($f['dari'] ?? null,    fn ($q, $v) => $q->whereDate('tanggal_sakit', '>=', $v))
            ->when($f['sampai'] ?? null,  fn ($q, $v) => $q->whereDate('tanggal_sakit', '<=', $v))
            ->when($f['q'] ?? null, fn ($q, $v) => $q->where(fn ($w) => $w
                ->where('gejala', 'like', "%{$v}%")
                ->orWhere('diagnosa', 'like', "%{$v}%")))
            ->when($f['status'] ?? null, function ($q, $v) {
                return match ($v) {
                    'sakit'  => $q->aktif(),
                    'sembuh' => $q->whereNotNull('tanggal_sembuh')->whereNull('tanggal_kematian'),
                    'mati'   => $q->whereNotNull('tanggal_kematian'),
                    default  => $q,
                };
            });
    }
}
