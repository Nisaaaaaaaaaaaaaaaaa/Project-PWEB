<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Produk;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk  = Produk::count();
        $totalPesanan = Order::count();

        return view('dashboard', compact('totalProduk', 'totalPesanan'));
    }
}