<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;

    public const STATUS_LABELS = [
        'unpaid' => 'Belum Lunas',
        'paid' => 'Lunas',
        'waived' => 'Dibebaskan',
    ];

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

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst((string) $this->status);
    }

    public static function statusOptions(): array
    {
        return self::STATUS_LABELS;
    }
}
