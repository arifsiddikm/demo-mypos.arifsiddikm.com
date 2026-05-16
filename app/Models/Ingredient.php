<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['supplier_id', 'name', 'unit', 'stock', 'min_stock', 'cost_per_unit'];
    protected $casts = ['stock' => 'decimal:3', 'min_stock' => 'decimal:3', 'cost_per_unit' => 'decimal:2'];

    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function menus() { return $this->belongsToMany(Menu::class, 'menu_ingredient')->withPivot('quantity_used'); }
    public function movements() { return $this->hasMany(InventoryMovement::class); }

    public function isLowStock(): bool {
        return $this->stock <= $this->min_stock;
    }
}
