<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetaniPesananController extends Controller
{
    public function index()
    {
        $produkIds = Produk::where('user_id', Auth::id())->pluck('id');
        $pesanan   = Order::whereIn('produk_id', $produkIds)->latest()->get();
        return view('petani.pesanan-saya', compact('pesanan'));
    }

    public function konfirmasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Selesai',
        ]);

        $produkIds = Produk::where('user_id', Auth::id())->pluck('id');
        $order     = Order::whereIn('produk_id', $produkIds)->findOrFail($id);
        $order->update(['status' => $request->status]);

        return redirect()->route('petani.pesanan.index')->with('success', 'Status pesanan diperbarui!');
    }
}