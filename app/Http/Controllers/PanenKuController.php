<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Order;
use Carbon\Carbon;

class PanenKuController extends Controller
{
    public function beranda()
    {
        $stats = [
            'total'    => Order::count(),
            'menunggu' => Order::menunggu()->count(),   
            'diproses' => Order::diproses()->count(),   
            'selesai'  => Order::selesai()->count(),    
        ];
        return view('beranda', compact('stats'));
    }
    public function produk()
    {
        $produk     = Produk::orderBy('is_default', 'desc')->orderBy('nama')->get();
        $defaultIds = Produk::default()->pluck('id')->toArray(); 
        return view('produk', compact('produk', 'defaultIds'));
    }
    public function simpanProduk(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|min:2',
            'harga' => 'required|integer|min:1',
            'stok'  => 'required|integer|min:0',
            'img'   => 'nullable|url',
        ], [
            'nama.required'  => 'Nama produk wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.min'      => 'Harga minimal Rp 1.',
            'stok.required'  => 'Stok wajib diisi.',
            'stok.min'       => 'Stok tidak boleh negatif.',
            'img.url'        => 'Format URL gambar tidak valid.',
        ]);

        Produk::create([
            'nama'       => $request->nama,
            'harga'      => (int) $request->harga,
            'stok'       => (int) $request->stok,
            'img'        => $request->img ?? '',
            'is_default' => false,
        ]);

        return redirect()->route('produk')
            ->with('success', "Produk \"{$request->nama}\" berhasil ditambahkan!");
    }
    public function editProduk($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk_edit', compact('produk'));
    }
    public function updateProduk(Request $request, $id)
    {
        $request->validate([
            'nama'  => 'required|string|min:2',
            'harga' => 'required|integer|min:1',
            'stok'  => 'required|integer|min:0',
            'img'   => 'nullable|url',
        ], [
            'nama.required'  => 'Nama produk wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'stok.required'  => 'Stok wajib diisi.',
            'img.url'        => 'Format URL gambar tidak valid.',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update([
            'nama'  => $request->nama,
            'harga' => (int) $request->harga,
            'stok'  => (int) $request->stok,
            'img'   => $request->img ?? $produk->img,
        ]);

        return redirect()->route('produk')
            ->with('success', "Produk \"{$request->nama}\" berhasil diupdate!");
    }
    public function hapusProduk($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->is_default) {
            return redirect()->route('produk')
                ->with('error', 'Produk default tidak bisa dihapus, hanya bisa diedit.');
        }

        $produk->delete();
        return redirect()->route('produk')
            ->with('success', 'Produk berhasil dihapus.');
    }
    public function preorder(Request $request)
    {
        $produk        = Produk::tersedia()->orderBy('nama')->get();
        $produkDipilih = $request->get('produk', '');
        return view('Preorder', compact('produk', 'produkDipilih'));
    }
    public function simpanPreorder(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|min:2',
            'telepon'   => 'required|string|min:10',
            'alamat'    => 'required|string|min:5',
            'produk_id' => 'required|exists:produk,id',
            'jumlah'    => 'required|integer|min:1',
        ], [
            'nama.required'      => 'Nama pembeli wajib diisi.',
            'telepon.required'   => 'Nomor telepon wajib diisi.',
            'telepon.min'        => 'Nomor telepon minimal 10 digit.',
            'alamat.required'    => 'Alamat pengiriman wajib diisi.',
            'produk_id.required' => 'Produk wajib dipilih.',
            'produk_id.exists'   => 'Produk tidak ditemukan.',
            'jumlah.required'    => 'Jumlah wajib diisi.',
            'jumlah.min'         => 'Jumlah minimal 1 kg.',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        $jumlah = (int) $request->jumlah;

        if ($produk->stok < $jumlah) {
            return back()
                ->with('error', "Stok {$produk->nama} hanya {$produk->stok} kg, kamu memesan {$jumlah} kg. Silakan kurangi jumlah.")
                ->withInput();
        }

        Order::create([
            'nama'        => $request->nama,
            'telepon'     => $request->telepon,
            'alamat'      => $request->alamat,
            'produk_id'   => $produk->id,
            'produk_nama' => $produk->nama,
            'jumlah'      => $jumlah,
            'total'       => $produk->harga * $jumlah,
            'status'      => 'Menunggu',
            'tanggal'     => Carbon::now()->translatedFormat('d F Y'),
        ]);

        $produk->decrement('stok', $jumlah);

        return redirect()->route('riwayat')
            ->with('success', "Pesanan {$jumlah} kg {$produk->nama} berhasil dibuat!");
    }
    public function riwayat(Request $request)
    {
        $semua  = Order::latest()->get();
        $status = $request->get('status');
        $orders = $status
            ? Order::where('status', $status)->latest()->get()
            : $semua;

        return view('riwayat', compact('semua', 'orders'));
    }

    public function ubahStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:Menunggu,Diproses,Selesai']);
        Order::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'Status pesanan diperbarui.');
    }

    public function hapusOrder($id)
    {
        Order::findOrFail($id)->delete();
        return back()->with('success', 'Pesanan berhasil dihapus.');
    }

    public function hapusSemua()
    {
        Order::truncate();
        return redirect()->route('riwayat')
            ->with('success', 'Semua riwayat berhasil dihapus.');
    }
    public function tentang()
    {
        return view('tentang');
    }
}