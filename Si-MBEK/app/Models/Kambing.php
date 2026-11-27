<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kambing extends Model
{
    protected $table = 'kambing';
    protected $primaryKey = 'id_kambing';

    protected $fillable = ['kode_kambing', 'kandang_id', 'ras', 'jenis_kelamin', 'tanggal_lahir', 'status'];

    protected $casts = ['tanggal_lahir' => 'date'];

    public function kandang()
    {
        return $this->belongsTo(Kandang::class, 'kandang_id', 'id_kandang');
    }

    public function perkawinanSebagaiPejantan()
    {
        return $this->hasMany(Perkawinan::class, 'pejantan_id', 'id_kambing');
    }

    public function perkawinanSebagaiIndukan()
    {
        return $this->hasMany(Perkawinan::class, 'indukan_id', 'id_kambing');
    }
}
