<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $table = 'pinjamans';

    // add fillable
    protected $fillable = [
        'is_approve',
        'status',
    ];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];

    public function scopeByPenanggungJawab($query)
    {
        return $query->where('penanggung_jawab_id', auth()->id());
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
