<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Journal extends Model
{
    use HasFactory;

    protected $table = 'journals';


    protected $fillable = [
        'date',
        'description',
        'account',
        'debit',
        'credit',
    ];

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function accountz()
    {
        return $this->belongsTo(Account::class, 'account');
    }

    public function scopePerBulan($query)
    {
        return $query->selectRaw("DATE_FORMAT(date, '%M %Y') as bulan")
                        ->groupBy('bulan')
                        ->orderBy('date', 'desc');
    }


}
