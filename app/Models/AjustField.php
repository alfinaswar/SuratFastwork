<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AjustField extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'ajust_fields';
    protected $guarded = ['id'];
}
