<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPenerimaEksternal extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'master_penerima_eksternals';
    protected $guarded = ['id'];

}
