<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Setting;
use App\Models\PrinterSetting;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function start(Request $request)
    {
        $data = $request->validate([
            'table_id'   => 'nullable|exists:tables,id',
            'order_type' => 'required|in:dine_in,takeaway',
        ]);

        // Check if table already has active transaction
        if (!empty($data['table_id'])) {
            $existing = Transaction::where('table_id', $data['table_id'])
                ->whereIn('status', ['open', 'hold'])->first();
            if ($existing) {
                return response()->json(['transaction' => $existing->load('items.menu')]);
            }
        }

        $transaction = Transaction::create([
            'invoice_number' => Transaction::generateInvoiceNumber(),
            'user_id'        => auth()->id(),
            'table_id'       => $data['table_id'] ?? null,
            'order_type'     => $data['order_type'],
            'status'         => 'open',
            'subtotal'       => 0,
            'tax'            => 0,
            'discount'       => 0,
            'total'          => 0,
        ]);

        if (!empty($data['table_id'])) {
            Table::find($data['table_id'])->update(['status' => 'occupied']);
        }

        return response()->json(['transaction' => $transaction->load('items.menu')]);
    }

    public function show(Transaction $transaction)
    {
        return response()->json(['transaction' => $transaction->load('items.menu', 'table')]);
    }

    public function addItem(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'menu_id'  => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'notes'    => 'nullable|string|max:255',
        ]);

        $menu = Menu::findOrFail($data['menu_id']);

        // Check if item already exists
        $existing = $transaction->items()->where('menu_id', $menu->id)->first();

        if ($existing) {
            $existing->increment('quantity', $data['quantity']);
            $existing->update(['subtotal' => $existing->price * $existing->quantity]);
        } else {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'menu_id'        => $menu->id,
                'menu_name'      => $menu->name,
                'price'          => $menu->price,
                'quantity'       => $data['quantity'],
                'subtotal'       => $menu->price * $data['quantity'],
                'notes'          => $data['notes'] ?? null,
            ]);
        }

        $this->recalculate($transaction);

        return response()->json(['transaction' => $transaction->fresh()->load('items.menu', 'table')]);
    }

    public function updateItem(Request $request, Transaction $transaction, TransactionItem $item)
    {
        $data = $request->validate(['quantity' => 'required|integer|min:1', 'notes' => 'nullable|string']);
        $item->update(['quantity' => $data['quantity'], 'subtotal' => $item->price * $data['quantity'], 'notes' => $data['notes'] ?? $item->notes]);
        $this->recalculate($transaction);
        return response()->json(['transaction' => $transaction->fresh()->load('items.menu', 'table')]);
    }

    public function removeItem(Transaction $transaction, TransactionItem $item)
    {
        $item->delete();
        $this->recalculate($transaction);
        return response()->json(['transaction' => $transaction->fresh()->load('items.menu', 'table')]);
    }

    public function hold(Transaction $transaction)
    {
        $transaction->update(['status' => 'hold']);
        return response()->json(['message' => 'Order di-hold.', 'transaction' => $transaction]);
    }

    public function checkout(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'payment_method' => 'required|in:cash,transfer,qris',
            'paid_amount'    => 'required|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string',
        ]);

        $discount      = $data['discount'] ?? 0;
        $taxPct        = (float) Setting::get('tax_percentage', 0);
        $subtotal      = $transaction->items->sum('subtotal');
        $tax           = $subtotal * ($taxPct / 100);
        $total         = $subtotal + $tax - $discount;
        $change        = $data['paid_amount'] - $total;

        $transaction->update([
            'payment_method' => $data['payment_method'],
            'subtotal'       => $subtotal,
            'tax'            => $tax,
            'discount'       => $discount,
            'total'          => $total,
            'paid_amount'    => $data['paid_amount'],
            'change_amount'  => max(0, $change),
            'status'         => 'paid',
            'notes'          => $data['notes'] ?? null,
            'paid_at'        => now(),
        ]);

        // Free table
        if ($transaction->table_id) {
            Table::find($transaction->table_id)->update(['status' => 'available']);
        }

        return response()->json([
            'message'     => 'Pembayaran berhasil!',
            'transaction' => $transaction->fresh()->load('items.menu', 'table'),
        ]);
    }

    public function cancel(Transaction $transaction)
    {
        $transaction->update(['status' => 'cancelled']);

        if ($transaction->table_id) {
            Table::find($transaction->table_id)->update(['status' => 'available']);
        }

        return response()->json(['message' => 'Transaksi dibatalkan.']);
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load('items.menu', 'table', 'user');
        $printer  = PrinterSetting::current();
        $settings = Setting::pluck('value', 'key');
        return view('pos.receipt', compact('transaction', 'printer', 'settings'));
    }

    public function printReceipt(Transaction $transaction)
    {
        $transaction->load('items.menu', 'table', 'user');
        $printer  = PrinterSetting::current();
        $settings = Setting::pluck('value', 'key');
        return view('pos.print', compact('transaction', 'printer', 'settings'));
    }

    private function recalculate(Transaction $transaction): void
    {
        $taxPct   = (float) Setting::get('tax_percentage', 0);
        $subtotal = $transaction->items()->sum('subtotal');
        $tax      = $subtotal * ($taxPct / 100);
        $total    = $subtotal + $tax - $transaction->discount;
        $transaction->update(['subtotal' => $subtotal, 'tax' => $tax, 'total' => $total]);
    }
}
