<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjamans';

    protected $fillable = [
        'user_id',
        'type',
        'tenor',
        'total_angsuran',
        'bunga',
        'jumlah_pengajuan',
        'penanggung_jawab',
        'is_approve',
        'status',
    ];
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function approveByRole(User $user)
    {
        if ($user->id == $this->penanggung_jawab_id && $this->is_approve == 0) {
            $this->update(['is_approve' => 1]);
        }
    }
}


