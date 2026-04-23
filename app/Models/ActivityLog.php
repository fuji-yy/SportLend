<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    public const ACTION_LABELS = [
        'create' => 'Tambah',
        'update' => 'Perbarui',
        'delete' => 'Hapus',
        'approve' => 'Disetujui',
        'reject' => 'Ditolak',
        'update_status' => 'Ubah Status',
    ];

    protected $fillable = ['user_id', 'action', 'model', 'model_id', 'description', 'old_data', 'new_data', 'ip_address', 'user_agent'];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute(): string
    {
        return self::ACTION_LABELS[$this->action] ?? ucwords(str_replace('_', ' ', (string) $this->action));
    }
}
