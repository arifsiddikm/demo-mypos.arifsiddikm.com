<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Table;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::with('activeTransaction.items')->where('is_active', true)->orderBy('name')->get();
        return response()->json($tables);
    }
}
