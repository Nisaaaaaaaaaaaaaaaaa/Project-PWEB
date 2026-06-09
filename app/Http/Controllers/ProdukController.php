<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class ProdukController extends Controller
{
public function index()
{
    $produk = Produk::all();
    $defaultIds = []; 
    return view('produk', compact('produk', 'defaultIds'));
}

    public function indexPelanggan()
    {
        $produk = Produk::tersedia()->with('user')->get();
        return view('produk-list', compact('produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok'  => 'required|integer|min:0',
        ]);

        Produk::create([
            'nama'  => $request->nama,
            'harga' => $request->harga,
            'stok'  => $request->stok,
            'img'   => $request->img,
        ]);

        return redirect()->route('produk')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk_edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok'  => 'required|integer|min:0',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update([
            'nama'  => $request->nama,
            'harga' => $request->harga,
            'stok'  => $request->stok,
            'img'   => $request->img,
        ]);

        return redirect()->route('produk')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Produk::findOrFail($id)->delete();
        return redirect()->route('produk')->with('success', 'Produk berhasil dihapus!');
    }
}