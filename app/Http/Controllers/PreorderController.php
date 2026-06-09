<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class PreorderController extends Controller
{
public function adminIndex()
{
    $query = Order::latest();

    if (request('status')) {
        $query->where('status', request('status'));
    }

    $preorders = $query->get();
    return view('pesanan-admin', compact('preorders'));
}

public function create()
{
    $produk        = Produk::tersedia()->get();
    $produkDipilih = request('produk'); 
    return view('preorder', compact('produk', 'produkDipilih'));
}

 public function store(Request $request)
{
    $request->validate([
        'nama'      => 'required|string|max:255',
        'telepon'   => 'required|string|max:20',
        'alamat'    => 'required|string',
        'produk_id' => 'required|exists:produk,id',
        'jumlah'    => 'required|integer|min:1',
    ]);

    $produk = Produk::findOrFail($request->produk_id);

    if ($request->jumlah > $produk->stok) {
        return back()->withErrors([
            'jumlah' => 'Stok tidak cukup! Stok tersedia: ' . $produk->stok . ' kg.'
        ])->withInput();
    }

    Order::create([
        'user_id'     => Auth::id(),
        'nama'        => $request->nama,
        'telepon'     => $request->telepon,
        'alamat'      => $request->alamat,
        'produk_id'   => $produk->id,
        'produk_nama' => $produk->nama,
        'jumlah'      => $request->jumlah,
        'total'       => $produk->harga * $request->jumlah,
        'tanggal'     => now()->format('Y-m-d'),
    ]);

    $produk->decrement('stok', $request->jumlah);

    return redirect()->route('riwayat')->with('success', 'Pesanan berhasil dibuat!');
}

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Selesai',
        ]);

        Order::findOrFail($id)->update(['status' => $request->status]);

        return redirect()->route('pesanan.admin')->with('success', 'Status pesanan diperbarui!');
    }
    public function bacaCookie(Request $request)
    {
        $raw = $request->cookie('preorder_draft');
        if (!$raw) {
            return response()->json(['status' => 'kosong']);
        }
        return response()->json([
            'status' => 'ok',
            'data'   => json_decode($raw, true),
        ]);
    }

    public function simpanCookie(Request $request)
    {
        $data   = $request->only(['nama', 'telepon', 'alamat', 'produk_id', 'jumlah']);
        $cookie = \Illuminate\Support\Facades\Cookie::make('preorder_draft', json_encode($data), 60 * 24 * 7);
        return response()->json(['status' => 'ok'])->withCookie($cookie);
    }
}