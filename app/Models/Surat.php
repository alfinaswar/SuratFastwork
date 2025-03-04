<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Surat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'surats';
    protected $guarded = ['id'];

    protected $casts = [
        'CarbonCopy' => 'json',
        'CarbonCopyEks' => 'json',
        'BlindCarbonCopy' => 'json',
        'BlindCarbonCopyEks' => 'json',
    ];

    public function getPenerima()
    {
        return $this->belongsTo(User::class, 'PenerimaSurat', 'id');
    }
    public function getPenerimaEks()
    {
        return $this->belongsTo(MasterPenerimaEksternal::class, 'PenerimaSuratEks', 'id');
    }
    public function NamaPengirim()
    {
        return $this->belongsTo(User::class, 'SentBy', 'id');
    }
    public function getCatatan()
    {
        return $this->hasOne(CatatanSurat::class, 'idSurat', 'id');
    }

    public function getPenulis()
    {
        return $this->belongsTo(User::class, 'DibuatOleh', 'id');
    }
    public function getVerifikator()
    {
        return $this->belongsTo(User::class, 'VerifiedBy', 'id');
    }


}
