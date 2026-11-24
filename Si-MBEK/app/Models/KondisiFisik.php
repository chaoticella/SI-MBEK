<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KondisiFisik extends Model
{
    protected $table = 'kondisi_fisik';
    protected $primaryKey = 'id_kondisi';

    /** Rentang normal kambing dewasa (acuan umum kesehatan ternak). */
    public const SUHU_MIN = 38.5;
    public const SUHU_MAX = 39.7;
    public const BCS_MIN  = 2.5;
    public const BCS_MAX  = 3.5;
    public const FAMACHA_BAHAYA = 4; // 4–5 = anemia, perlu tindakan

    public const NAFSU_MAKAN = ['baik' => 'Baik', 'sedang' => 'Sedang', 'buruk' => 'Buruk'];

    protected $fillable = [
        'id_kambing', 'id_user', 'tanggal_cek', 'berat_badan', 'tinggi_badan',
        'nafsu_makan', 'suhu_tubuh', 'bcs', 'famacha', 'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_cek'  => 'date',
            'berat_badan'  => 'decimal:2',
            'tinggi_badan' => 'decimal:1',
            'suhu_tubuh'   => 'decimal:1',
            'bcs'          => 'decimal:1',
            'famacha'      => 'integer',
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

    /* ---------- Helper status ---------- */

    public function suhuAbnormal(): bool
    {
        return $this->suhu_tubuh < self::SUHU_MIN || $this->suhu_tubuh > self::SUHU_MAX;
    }

    public function famachaBahaya(): bool
    {
        return $this->famacha !== null && $this->famacha >= self::FAMACHA_BAHAYA;
    }

    public function bcsAbnormal(): bool
    {
        return $this->bcs !== null && ($this->bcs < self::BCS_MIN || $this->bcs > self::BCS_MAX);
    }

    /** Daftar peringatan untuk ditampilkan sebagai badge. */
    public function peringatan(): array
    {
        $w = [];
        if ($this->suhuAbnormal())   $w[] = $this->suhu_tubuh > self::SUHU_MAX ? 'Demam' : 'Hipotermia';
        if ($this->famachaBahaya())  $w[] = 'Anemia (FAMACHA)';
        if ($this->bcsAbnormal())    $w[] = $this->bcs < self::BCS_MIN ? 'Terlalu kurus' : 'Terlalu gemuk';
        if ($this->nafsu_makan === 'buruk') $w[] = 'Nafsu makan buruk';
        return $w;
    }

    /** Selisih berat dibanding pencatatan sebelumnya (kg), null jika belum ada. */
    public function selisihBerat(): ?float
    {
        $sebelumnya = static::where('id_kambing', $this->id_kambing)
            ->where('tanggal_cek', '<', $this->tanggal_cek)
            ->orderByDesc('tanggal_cek')
            ->value('berat_badan');

        return $sebelumnya === null ? null : round($this->berat_badan - $sebelumnya, 2);
    }

    /* ---------- Scope filter ---------- */

    public function scopeFilter(Builder $q, array $f): Builder
    {
        return $q
            ->when($f['kambing'] ?? null, fn ($q, $v) => $q->where('id_kambing', $v))
            ->when($f['dari'] ?? null,    fn ($q, $v) => $q->whereDate('tanggal_cek', '>=', $v))
            ->when($f['sampai'] ?? null,  fn ($q, $v) => $q->whereDate('tanggal_cek', '<=', $v))
            ->when($f['nafsu'] ?? null,   fn ($q, $v) => $q->where('nafsu_makan', $v));
    }
}
