<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Setting;

class LandingController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->where('slug', '!=', 'all')->orderBy('sort_order')->get();
        $menus      = Menu::with('category')->where('is_available', true)->orderBy('category_id')->get();
        $settings   = Setting::pluck('value', 'key');

        return view('landing.index', compact('categories', 'menus', 'settings'));
    }
}
