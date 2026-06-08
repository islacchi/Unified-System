<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        $defaultPassword = Setting::getValue('default_password', 'Staff@2024');
        return view('settings.index', compact('defaultPassword'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'default_password' => 'required|string|min:6',
        ]);

        Setting::updateOrCreate(
            ['key' => 'default_password'],
            ['value' => $data['default_password']]
        );

        return redirect()->route('settings.edit')
            ->with('message', 'Default password updated successfully.');
    }
}