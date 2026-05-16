<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\PrinterSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $keys = ['cafe_name', 'cafe_address', 'cafe_phone', 'cafe_email', 'cafe_description', 'cafe_tagline', 'tax_percentage'];
        foreach ($keys as $key) {
            Setting::set($key, $request->get($key));
        }
        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function printer()
    {
        $printer = PrinterSetting::current();
        return view('admin.settings.printer', compact('printer'));
    }

    public function updatePrinter(Request $request)
    {
        $data = $request->validate([
            'printer_name'  => 'nullable|string|max:100',
            'printer_type'  => 'required|in:thermal,laser',
            'auto_print'    => 'boolean',
            'paper_size'    => 'required|in:58mm,80mm,A4',
            'header_text'   => 'nullable|string',
            'footer_text'   => 'nullable|string',
        ]);
        $data['auto_print'] = $request->boolean('auto_print');
        PrinterSetting::current()->update($data);
        return back()->with('success', 'Pengaturan printer disimpan.');
    }
}
