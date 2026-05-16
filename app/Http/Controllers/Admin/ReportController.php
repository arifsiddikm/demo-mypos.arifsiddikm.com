<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\InventoryMovement;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.report.index');
    }

    public function transactions(Request $request)
    {
        $from   = $request->get('from', Carbon::today()->toDateString());
        $to     = $request->get('to', Carbon::today()->toDateString());
        $status = $request->get('status', 'paid');

        $transactions = Transaction::with(['user', 'table', 'items'])
            ->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->get();

        $totalRevenue = $transactions->where('status', 'paid')->sum('total');
        $totalTrx     = $transactions->count();

        return view('admin.report.transactions', compact('transactions', 'from', 'to', 'status', 'totalRevenue', 'totalTrx'));
    }

    public function inventory(Request $request)
    {
        $from = $request->get('from', Carbon::today()->subDays(30)->toDateString());
        $to   = $request->get('to', Carbon::today()->toDateString());
        $type = $request->get('type', 'all');

        $movements = InventoryMovement::with(['ingredient', 'supplier', 'user'])
            ->whereBetween('movement_date', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ])
            ->when($type !== 'all', fn($q) => $q->where('type', $type))
            ->orderByDesc('movement_date')
            ->get();

        $ingredients = Ingredient::with('supplier')->orderBy('name')->get();

        return view('admin.report.inventory', compact('movements', 'from', 'to', 'type', 'ingredients'));
    }

    public function exportTransactionPdf(Request $request)
    {
        $from         = $request->get('from', Carbon::today()->toDateString());
        $to           = $request->get('to', Carbon::today()->toDateString());
        $transactions = Transaction::with(['user', 'table', 'items'])
            ->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()])
            ->where('status', 'paid')->orderByDesc('created_at')->get();
        $totalRevenue = $transactions->sum('total');

        $pdf = view('admin.report.pdf.transactions', compact('transactions', 'from', 'to', 'totalRevenue'));
        return response($pdf)->header('Content-Type', 'text/html');
    }

    public function exportTransactionExcel(Request $request)
    {
        $from         = $request->get('from', Carbon::today()->toDateString());
        $to           = $request->get('to', Carbon::today()->toDateString());
        $transactions = Transaction::with(['user', 'table'])
            ->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()])
            ->where('status', 'paid')->orderByDesc('created_at')->get();

        $filename = 'laporan-transaksi-' . $from . '-sd-' . $to . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No Invoice', 'Tanggal', 'Kasir', 'Meja', 'Tipe', 'Metode', 'Total', 'Status']);
            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->invoice_number,
                    $t->created_at->format('d/m/Y H:i'),
                    $t->user->name,
                    $t->table?->name ?? 'Takeaway',
                    $t->order_type,
                    $t->payment_method,
                    $t->total,
                    $t->status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportInventoryPdf(Request $request)
    {
        $from      = $request->get('from', Carbon::today()->subDays(30)->toDateString());
        $to        = $request->get('to', Carbon::today()->toDateString());
        $movements = InventoryMovement::with(['ingredient', 'supplier', 'user'])
            ->whereBetween('movement_date', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()])
            ->orderByDesc('movement_date')->get();

        $pdf = view('admin.report.pdf.inventory', compact('movements', 'from', 'to'));
        return response($pdf)->header('Content-Type', 'text/html');
    }

    public function exportInventoryExcel(Request $request)
    {
        $from      = $request->get('from', Carbon::today()->subDays(30)->toDateString());
        $to        = $request->get('to', Carbon::today()->toDateString());
        $movements = InventoryMovement::with(['ingredient', 'supplier', 'user'])
            ->whereBetween('movement_date', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()])
            ->orderByDesc('movement_date')->get();

        $filename = 'laporan-inventory-' . $from . '-sd-' . $to . '.csv';
        $headers  = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];

        $callback = function () use ($movements) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Bahan', 'Tipe', 'Jumlah', 'Satuan', 'Harga/Unit', 'Supplier', 'Catatan', 'Oleh']);
            foreach ($movements as $m) {
                fputcsv($file, [
                    $m->movement_date->format('d/m/Y H:i'),
                    $m->ingredient->name,
                    strtoupper($m->type),
                    $m->quantity,
                    $m->ingredient->unit,
                    $m->cost_per_unit,
                    $m->supplier?->name ?? '-',
                    $m->notes ?? '-',
                    $m->user->name,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
