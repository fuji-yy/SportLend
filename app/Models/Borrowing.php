<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    public const STATUS_LABELS = [
        'pending' => 'Menunggu',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'returned' => 'Selesai',
    ];

    protected $fillable = ['user_id', 'tool_id', 'quantity', 'borrow_date', 'due_date', 'status', 'purpose', 'notes'];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function return()
    {
        return $this->hasOne(Return_model::class);
    }

    public function fine()
    {
        return $this->hasOne(Fine::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst((string) $this->status);
    }
}
