<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Menu;
use App\Models\Ingredient;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today      = Carbon::today();
        $thisMonth  = Carbon::now()->startOfMonth();
        
        $todaySales     = Transaction::where('status', 'paid')->whereDate('paid_at', $today)->sum('total');
        $monthSales     = Transaction::where('status', 'paid')->where('paid_at', '>=', $thisMonth)->sum('total');
        $todayTrx       = Transaction::where('status', 'paid')->whereDate('paid_at', $today)->count();
        $openOrders     = Transaction::whereIn('status', ['open', 'hold'])->count();
        $totalMenus     = Menu::where('is_available', true)->count();
        $lowStock       = Ingredient::whereRaw('stock <= min_stock')->count();

        // Last 7 days sales chart
        $salesChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $salesChart[] = [
                'date'  => $date->format('d M'),
                'total' => Transaction::where('status', 'paid')->whereDate('paid_at', $date)->sum('total'),
            ];
        }

        // Recent transactions
        $recentTransactions = Transaction::with(['user', 'table'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Top menus
        $topMenus = \App\Models\TransactionItem::selectRaw('menu_name, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('menu_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'todaySales', 'monthSales', 'todayTrx', 'openOrders',
            'totalMenus', 'lowStock', 'salesChart', 'recentTransactions', 'topMenus'
        ));
    }
}
