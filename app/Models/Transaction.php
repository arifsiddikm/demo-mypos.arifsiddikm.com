<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number', 'user_id', 'table_id', 'order_type', 'status',
        'payment_method', 'subtotal', 'tax', 'discount', 'total',
        'paid_amount', 'change_amount', 'notes', 'paid_at'
    ];

    protected $casts = [
        'subtotal'      => 'decimal:2',
        'tax'           => 'decimal:2',
        'discount'      => 'decimal:2',
        'total'         => 'decimal:2',
        'paid_amount'   => 'decimal:2',
        'change_amount' => 'decimal:2',
        'paid_at'       => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function table() { return $this->belongsTo(Table::class); }
    public function items() { return $this->hasMany(TransactionItem::class); }

    public static function generateInvoiceNumber(): string {
        $prefix = 'INV-' . date('Ymd') . '-';
        $last = self::where('invoice_number', 'like', $prefix . '%')->orderByDesc('id')->first();
        $seq = $last ? (intval(substr($last->invoice_number, -4)) + 1) : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
