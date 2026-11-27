<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perkawinan extends Model
{
    protected $fillable = ['tanggal_kawin', 'pejantan_id', 'indukan_id', 'estimasi_melahirkan', 'jumlah_anak_lahir'];

    protected $casts = [
        'tanggal_kawin' => 'date',
        'estimasi_melahirkan' => 'date',
    ];

    public function pejantan()
    {
        return $this->belongsTo(Kambing::class, 'pejantan_id');
    }

    public function indukan()
    {
        return $this->belongsTo(Kambing::class, 'indukan_id');
    }
}
