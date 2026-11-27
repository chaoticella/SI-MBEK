<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kambing extends Model
{
    protected $fillable = ['kode_kambing', 'kandang_id', 'ras', 'jenis_kelamin', 'tanggal_lahir', 'status'];

    protected $casts = ['tanggal_lahir' => 'date'];

    public function kandang()
    {
        return $this->belongsTo(Kandang::class);
    }

    public function perkawinanSebagaiPejantan()
    {
        return $this->hasMany(Perkawinan::class, 'pejantan_id');
    }

    public function perkawinanSebagaiIndukan()
    {
        return $this->hasMany(Perkawinan::class, 'indukan_id');
    }
}
