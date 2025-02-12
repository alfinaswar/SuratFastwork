<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\AjustField;

class MasterFields extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'master_fields';
    protected $guarded = ['id'];


}
