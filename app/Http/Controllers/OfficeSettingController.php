<?php

namespace App\Http\Controllers;

use App\Models\OfficeSetting;
use Illuminate\Http\Request;

class OfficeSettingController extends Controller
{
    public function edit()
    {
        $office = OfficeSetting::current() ?? new OfficeSetting();

        return view('office.edit', compact('office'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:100'],
            'latitude'        => ['required', 'numeric', 'between:-90,90'],
            'longitude'       => ['required', 'numeric', 'between:-180,180'],
            'radius_m'        => ['required', 'integer', 'min:10', 'max:5000'],
            'work_start_time' => ['required', 'date_format:H:i'],
        ]);

        $office = OfficeSetting::current();
        $office ? $office->update($data) : OfficeSetting::create($data);

        return redirect()->route('office-settings.edit')->with('success', 'Pengaturan kantor disimpan.');
    }
}