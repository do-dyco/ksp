<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jenis_simpanan extends Model
{
    use HasFactory;

    protected $table = 'jenis_simpanan';

    // add fillable
    protected $fillable = [
        'nama_simpanan',
    ];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];
}
