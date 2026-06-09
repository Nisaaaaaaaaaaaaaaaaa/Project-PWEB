<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetaniProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::where('user_id', Auth::id())->paginate(10);
        return view('petani.produk-saya', compact('produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok'  => 'required|integer|min:0',
            'img'   => 'nullable|url',
        ]);

        Produk::create([
            'user_id' => Auth::id(),
            'nama'    => $request->nama,
            'harga'   => $request->harga,
            'stok'    => $request->stok,
            'img'     => $request->img,
        ]);

        return redirect()->route('petani.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $produk = Produk::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('petani.produk-edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nama'  => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok'  => 'required|integer|min:0',
            'img'   => 'nullable|url',
        ]);

        $produk->update([
            'nama'  => $request->nama,
            'harga' => $request->harga,
            'stok'  => $request->stok,
            'img'   => $request->img,
        ]);

        return redirect()->route('petani.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $produk = Produk::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $produk->delete();
        return redirect()->route('petani.produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}