<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petani extends Model
{
    protected $fillable = [
    'user_id',
    'kode',
    'nama',
    'email',
    'telepon',
    'komoditas',
    'foto',
    ];
}