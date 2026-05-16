<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $from     = $request->get('from', Carbon::today()->subDays(30)->toDateString());
        $to       = $request->get('to', Carbon::today()->toDateString());
        $status   = $request->get('status', 'all');
        $kasir    = $request->get('kasir', 'all');
        $payment  = $request->get('payment', 'all');
        $type     = $request->get('type', 'all');
        $search   = $request->get('search', '');

        $query = Transaction::with(['user', 'table', 'items.menu.category'])
            ->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);

        if ($status !== 'all') $query->where('status', $status);
        if ($kasir  !== 'all') $query->where('user_id', $kasir);
        if ($payment !== 'all') $query->where('payment_method', $payment);
        if ($type   !== 'all') $query->where('order_type', $type);
        if ($search) $query->where('invoice_number', 'like', "%$search%");

        $transactions = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        // Stats
        $allFiltered = $query->get();
        $totalRevenue   = Transaction::whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()])
            ->when($kasir !== 'all', fn($q) => $q->where('user_id', $kasir))
            ->where('status', 'paid')->sum('total');
        $totalPaid      = Transaction::whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()])
            ->when($kasir !== 'all', fn($q) => $q->where('user_id', $kasir))
            ->where('status', 'paid')->count();
        $totalCancelled = Transaction::whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()])
            ->when($kasir !== 'all', fn($q) => $q->where('user_id', $kasir))
            ->where('status', 'cancelled')->count();

        $kasirList = User::select('id','name')->orderBy('name')->get();

        return view('admin.transaction.index', compact(
            'transactions','from','to','status','kasir','payment','type','search',
            'totalRevenue','totalPaid','totalCancelled','kasirList'
        ));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'table', 'items.menu.category']);
        return view('admin.transaction.show', compact('transaction'));
    }

    public function exportPdf(Request $request)
    {
        $from    = $request->get('from', Carbon::today()->subDays(30)->toDateString());
        $to      = $request->get('to', Carbon::today()->toDateString());
        $status  = $request->get('status', 'all');
        $kasir   = $request->get('kasir', 'all');
        $payment = $request->get('payment', 'all');
        $type    = $request->get('type', 'all');

        $transactions = Transaction::with(['user', 'table', 'items'])
            ->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()])
            ->when($status  !== 'all', fn($q) => $q->where('status', $status))
            ->when($kasir   !== 'all', fn($q) => $q->where('user_id', $kasir))
            ->when($payment !== 'all', fn($q) => $q->where('payment_method', $payment))
            ->when($type    !== 'all', fn($q) => $q->where('order_type', $type))
            ->orderByDesc('created_at')
            ->get();

        $totalRevenue = $transactions->where('status','paid')->sum('total');
        $totalTrx     = $transactions->count();

        $html = view('admin.transaction.pdf', compact('transactions','from','to','totalRevenue','totalTrx'))->render();

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="transaksi-' . $from . '-' . $to . '.html"');
    }

    public function exportExcel(Request $request)
    {
        $from    = $request->get('from', Carbon::today()->subDays(30)->toDateString());
        $to      = $request->get('to', Carbon::today()->toDateString());
        $status  = $request->get('status', 'all');
        $kasir   = $request->get('kasir', 'all');
        $payment = $request->get('payment', 'all');
        $type    = $request->get('type', 'all');

        $transactions = Transaction::with(['user', 'table', 'items'])
            ->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()])
            ->when($status  !== 'all', fn($q) => $q->where('status', $status))
            ->when($kasir   !== 'all', fn($q) => $q->where('user_id', $kasir))
            ->when($payment !== 'all', fn($q) => $q->where('payment_method', $payment))
            ->when($type    !== 'all', fn($q) => $q->where('order_type', $type))
            ->orderByDesc('created_at')
            ->get();

        // Build XLSX manually using OpenDocument Spreadsheet XML
        // (PhpSpreadsheet not installed — using XML/XLSX-compatible CSV approach)
        // Note: untuk true .xlsx, install: composer require phpoffice/phpspreadsheet
        $filename = 'transaksi-' . $from . '-sd-' . $to . '.xlsx';

        // Kita pakai PhpSpreadsheet jika ada, fallback ke CSV dengan extension xlsx
        if (class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            return $this->exportWithPhpSpreadsheet($transactions, $from, $to, $filename);
        }

        // Fallback: CSV dengan proper headers untuk Excel
        return $this->exportAsCsv($transactions, $filename);
    }

    private function exportWithPhpSpreadsheet($transactions, $from, $to, $filename)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Transaksi');

        // Header styling
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '5C3D1E']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];

        // Title row
        $sheet->setCellValue('A1', 'LAPORAN DATA TRANSAKSI MyPOS');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Periode: ' . $from . ' s/d ' . $to);
        $sheet->mergeCells('A2:J2');

        // Headers
        $headers = ['No','No Invoice','Tanggal','Kasir','Meja','Tipe Order','Metode Bayar','Subtotal','Tax','Diskon','Total','Status'];
        foreach ($headers as $col => $header) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1) . '4';
            $sheet->setCellValue($cell, $header);
        }
        $sheet->getStyle('A4:L4')->applyFromArray($headerStyle);

        // Data rows
        $row = 5;
        foreach ($transactions as $i => $t) {
            $sheet->setCellValue('A'.$row, $i + 1);
            $sheet->setCellValue('B'.$row, $t->invoice_number);
            $sheet->setCellValue('C'.$row, $t->created_at->format('d/m/Y H:i'));
            $sheet->setCellValue('D'.$row, $t->user->name);
            $sheet->setCellValue('E'.$row, $t->table?->name ?? 'Takeaway');
            $sheet->setCellValue('F'.$row, str_replace('_', ' ', strtoupper($t->order_type)));
            $sheet->setCellValue('G'.$row, strtoupper($t->payment_method ?? '-'));
            $sheet->setCellValue('H'.$row, (float)$t->subtotal);
            $sheet->setCellValue('I'.$row, (float)$t->tax);
            $sheet->setCellValue('J'.$row, (float)$t->discount);
            $sheet->setCellValue('K'.$row, (float)$t->total);
            $sheet->setCellValue('L'.$row, strtoupper($t->status));

            // Stripe
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:L{$row}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FDF8F0');
            }
            $row++;
        }

        // Total row
        $sheet->setCellValue('A'.$row, '');
        $sheet->setCellValue('J'.$row, 'TOTAL LUNAS:');
        $sheet->setCellValue('K'.$row, $transactions->where('status','paid')->sum('total'));
        $sheet->getStyle("J{$row}:K{$row}")->getFont()->setBold(true);

        // Auto width
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'mypos_');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function exportAsCsv($transactions, $filename)
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No Invoice','Tanggal','Kasir','Meja','Tipe Order','Metode Bayar','Subtotal','Tax','Diskon','Total','Status']);
            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->invoice_number,
                    $t->created_at->format('d/m/Y H:i'),
                    $t->user->name,
                    $t->table?->name ?? 'Takeaway',
                    str_replace('_',' ', $t->order_type),
                    $t->payment_method ?? '-',
                    $t->subtotal,
                    $t->tax,
                    $t->discount,
                    $t->total,
                    $t->status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
