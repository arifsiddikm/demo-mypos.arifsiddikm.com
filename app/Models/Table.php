<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Table extends Model {
    protected $fillable = ['name', 'capacity', 'status', 'pos_x', 'pos_y', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function transactions() { return $this->hasMany(Transaction::class); }
    public function activeTransaction() {
        return $this->hasOne(Transaction::class)->whereIn('status', ['open', 'hold']);
    }
}
