<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FineSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'late_fee_per_day',
        'damage_light_per_item',
        'damage_heavy_per_item',
        'updated_by',
    ];

    public static function defaultValues(): array
    {
        return [
            'late_fee_per_day' => (int) config('fine.late_fee_per_day', 2000),
            'damage_light_per_item' => (int) config('fine.damage_fee_per_item.rusak_ringan', 10000),
            'damage_heavy_per_item' => (int) config('fine.damage_fee_per_item.rusak_berat', 30000),
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], static::defaultValues());
    }
}
