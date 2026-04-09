<?php

namespace App\Services;

use App\Models\Borrowing;
use App\Models\FineSetting;
use App\Models\Return_model;
use Carbon\Carbon;

class FineCalculatorService
{
    protected static ?FineSetting $cachedSetting = null;

    /**
     * Calculate fine values based on due date and return condition.
     */
    public static function calculate(Borrowing $borrowing, Return_model $return): array
    {
        $daysLate = max(
            0,
            Carbon::parse($borrowing->due_date)->startOfDay()->diffInDays(
                Carbon::parse($return->return_date)->startOfDay(),
                false
            )
        );

        $qty = max(1, (int) $return->quantity_returned);

        $setting = self::setting();

        $latePerDay = (int) $setting['late_fee_per_day'];
        $damagePerItem = match ($return->condition) {
            'rusak_ringan' => (int) $setting['damage_light_per_item'],
            'rusak_berat' => (int) $setting['damage_heavy_per_item'],
            default => 0,
        };

        $amountLate = $daysLate * $latePerDay * $qty;
        $amountDamage = $damagePerItem * $qty;

        return [
            'days_late' => $daysLate,
            'amount_late' => $amountLate,
            'amount_damage' => $amountDamage,
            'amount_total' => $amountLate + $amountDamage,
        ];
    }

    protected static function setting(): array
    {
        if (!self::$cachedSetting) {
            self::$cachedSetting = FineSetting::current();
        }

        return [
            'late_fee_per_day' => self::$cachedSetting->late_fee_per_day,
            'damage_light_per_item' => self::$cachedSetting->damage_light_per_item,
            'damage_heavy_per_item' => self::$cachedSetting->damage_heavy_per_item,
        ];
    }
}
