<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrinterSetting extends Model
{
    protected $fillable = ['printer_name', 'printer_type', 'auto_print', 'paper_size', 'header_text', 'footer_text'];
    protected $casts = ['auto_print' => 'boolean'];

    public static function current(): static
    {
        return static::firstOrCreate([], [
            'printer_type' => 'thermal',
            'auto_print'   => false,
            'paper_size'   => '58mm',
        ]);
    }
}
