<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->keyBy('key');
        return view('admin.site-settings.index', compact('settings'));
    }

    public function show($key)
    {
        $settings = SiteSetting::all()->keyBy('key');
        return view('admin.site-settings.index', compact('settings'));
    }

    public function update(Request $request, $key)
    {
        $validated = $request->validate([
            'value' => 'nullable|image|max:51200',
        ]);

        $setting = SiteSetting::where('key', $key)->firstOrNew();

        if ($request->hasFile('value')) {
            if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                Storage::disk('public')->delete($setting->value);
            }
            $setting->value = $request->file('value')->store('settings', 'public');
            $setting->type = 'image';
        }

        $setting->key = $key;
        $setting->save();

        return back()->with('success', 'Setting updated.');
    }
}
