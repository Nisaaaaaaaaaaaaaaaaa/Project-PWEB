<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Produk;

class BerandaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user && $user->role === 'petani') {
            $produkIds = Produk::where('user_id', $user->id)->pluck('id');
            $stats = [
                'total'    => Order::whereIn('produk_id', $produkIds)->count(),
                'menunggu' => Order::whereIn('produk_id', $produkIds)->where('status', 'Menunggu')->count(),
                'diproses' => Order::whereIn('produk_id', $produkIds)->where('status', 'Diproses')->count(),
                'selesai'  => Order::whereIn('produk_id', $produkIds)->where('status', 'Selesai')->count(),
            ];
        } else {
            $stats = [
                'total'    => Order::count(),
                'menunggu' => Order::where('status', 'Menunggu')->count(),
                'diproses' => Order::where('status', 'Diproses')->count(),
                'selesai'  => Order::where('status', 'Selesai')->count(),
            ];
        }

        $count = session('kunjungan_count', 0) + 1;
        session(['kunjungan_count' => $count]);
        if ($count === 1) {
            session(['kunjungan_pertama' => now()->format('d M Y H:i:s')]);
        }
        session(['kunjungan_terakhir' => now()->format('d M Y H:i:s')]);

        return view('beranda', [
            'stats'              => $stats,
            'kunjungan_count'    => $count,
            'kunjungan_pertama'  => session('kunjungan_pertama'),
            'kunjungan_terakhir' => session('kunjungan_terakhir'),
        ]);
    }
}