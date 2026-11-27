<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * ⚠️ PLACEHOLDER — model milik modul Kambing.
 * Kalau temanmu sudah punya model Kambing, cukup TAMBAHKAN 4 relasi kesehatan
 * di bawah (kondisiFisik, kondisiTerakhir, penyakit, penyakitAktif)
 * ke model punya dia.
 */
class Kambing extends Model
{
    protected $table = 'kambing';
    protected $primaryKey = 'id_kambing';

    protected $fillable = [
        'id_kandang', 'jenis_ras', 'jenis_kelamin',
        'tanggal_lahir', 'status_ternak', 'status_aktif',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'status_aktif'  => 'boolean',
        ];
    }

    public function kandang(): BelongsTo
    {
        return $this->belongsTo(Kandang::class, 'id_kandang', 'id_kandang');
    }

    /* ---------- Relasi Modul 3: Kesehatan ---------- */

    public function kondisiFisik(): HasMany
    {
        return $this->hasMany(KondisiFisik::class, 'id_kambing', 'id_kambing');
    }

    public function kondisiTerakhir(): HasOne
    {
        return $this->hasOne(KondisiFisik::class, 'id_kambing', 'id_kambing')
            ->latestOfMany('tanggal_cek');
    }

    public function penyakit(): HasMany
    {
        return $this->hasMany(Penyakit::class, 'id_kambing', 'id_kambing');
    }

    public function penyakitAktif(): HasMany
    {
        return $this->penyakit()->aktif();
    }

    /** Label tampilan, mis. "#KB-007 · Etawa (Betina)" */
    public function getLabelAttribute(): string
    {
        return sprintf('#KB-%03d · %s (%s)',
            $this->id_kambing, $this->jenis_ras, ucfirst($this->jenis_kelamin));
    }

    public function getKodeAttribute(): string
    {
        return sprintf('KB-%03d', $this->id_kambing);
    }
}
