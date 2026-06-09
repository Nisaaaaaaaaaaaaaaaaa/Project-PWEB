<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'user_id',
        'nama',
        'harga',
        'stok',
        'img',
        'is_default',
    ];

    protected $casts = [
        'harga'      => 'integer',
        'stok'       => 'integer',
        'is_default' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'produk_id');
    }

    public function scopeTersedia(Builder $query): Builder
    {
        return $query->where('stok', '>', 0);
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    public function scopeStokRendah(Builder $query): Builder
    {
        return $query->where('stok', '>', 0)->where('stok', '<=', 20);
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getStatusStokAttribute(): string
    {
        if ($this->stok <= 0)  return 'habis';
        if ($this->stok <= 20) return 'warn';
        return 'ok';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}