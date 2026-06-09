<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',      
        'nama',
        'telepon',
        'alamat',
        'produk_id',
        'produk_nama',
        'jumlah',
        'total',
        'status',
        'tanggal',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'total'  => 'integer',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeMenunggu(Builder $query): Builder
    {
        return $query->where('status', 'Menunggu');
    }

    public function scopeDiproses(Builder $query): Builder
    {
        return $query->where('status', 'Diproses');
    }

    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('status', 'Selesai');
    }

    public function getTotalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}