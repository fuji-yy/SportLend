<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\FineSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FineSettingController extends Controller
{
    public function edit()
    {
        $setting = FineSetting::current();

        return view('admin.fines.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'late_fee_per_day' => 'required|integer|min:0',
            'damage_light_per_item' => 'required|integer|min:0',
            'damage_heavy_per_item' => 'required|integer|min:0',
        ]);

        $setting = FineSetting::current();
        $oldData = $setting->toArray();

        $setting->update([
            'late_fee_per_day' => (int) $request->late_fee_per_day,
            'damage_light_per_item' => (int) $request->damage_light_per_item,
            'damage_heavy_per_item' => (int) $request->damage_heavy_per_item,
            'updated_by' => Auth::id(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'model' => 'FineSetting',
            'model_id' => $setting->id,
            'description' => 'Memperbarui pengaturan tarif denda',
            'old_data' => $oldData,
            'new_data' => $setting->fresh()->toArray(),
        ]);

        return redirect()->route('admin.fines.settings.edit')->with('success', 'Pengaturan tarif denda berhasil diperbarui.');
    }
}
