<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrowing_id',
        'days_late',
        'amount_late',
        'amount_damage',
        'amount_total',
        'status',
        'paid_at',
        'notes_admin',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }
}
