<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminSetting;

class AdminSettingController extends Controller
{
    public function index()
    {
        $admin_setting = AdminSetting::get();
        return view('admin.settings', compact('admin_setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        AdminSetting::updateOrCreate(['key' => $request->key], ['value' => $request->value]);

        return redirect()->route('admin.settings')->with('success', 'Settings saved successfully!');
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'key' => 'required|string|max:255',
    //         'value' => 'required|string|max:255',
    //     ]);

    //     $setting = AdminSetting::findOrFail($id);
    //     $setting->key = $request->key;
    //     $setting->value = $request->value;
    //     $setting->save();

    //     return redirect()->route('admin.settings')->with('success', 'Setting updated successfully!');
    // }

    public function updateAll(Request $request)
    {
        $settings = $request->input('settings', []);

        foreach ($settings as $id => $value) {
            \App\Models\AdminSetting::where('id', $id)->update(['value' => $value]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
