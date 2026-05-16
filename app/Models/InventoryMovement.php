<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = ['ingredient_id', 'supplier_id', 'user_id', 'type', 'quantity', 'cost_per_unit', 'notes', 'movement_date'];
    protected $casts = ['movement_date' => 'datetime', 'quantity' => 'decimal:3', 'cost_per_unit' => 'decimal:2'];

    public function ingredient() { return $this->belongsTo(Ingredient::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function user() { return $this->belongsTo(User::class); }
}
