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
        'BlindCarbonCopy' => 'json'
    ];

    public function getPenerima()
    {
        return $this->belongsTo(User::class, 'PenerimaSurat', 'id');
    }

    public function getPenulis()
    {
        return $this->belongsTo(User::class, 'DibuatOleh', 'id');
    }
}
