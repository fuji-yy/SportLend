<?php

return [
    // Fine rates in IDR.
    'late_fee_per_day' => env('FINE_LATE_FEE_PER_DAY', 2000),

    // Damage fine per returned item.
    'damage_fee_per_item' => [
        'baik' => 0,
        'rusak_ringan' => env('FINE_DAMAGE_LIGHT_PER_ITEM', 10000),
        'rusak_berat' => env('FINE_DAMAGE_HEAVY_PER_ITEM', 30000),
    ],
];
