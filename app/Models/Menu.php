<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['category_id', 'name', 'description', 'price', 'image', 'is_available', 'sort_order'];
    protected $casts = ['is_available' => 'boolean', 'price' => 'decimal:2'];

    public function category() { return $this->belongsTo(Category::class); }
    public function ingredients() { return $this->belongsToMany(Ingredient::class, 'menu_ingredient')->withPivot('quantity_used'); }
}
