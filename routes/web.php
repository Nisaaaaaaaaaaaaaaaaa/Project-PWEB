<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetaniController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PreferensiController;
use App\Http\Controllers\PetaniProdukController;
use App\Http\Controllers\PetaniPesananController;

Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/tentang', function () {
    return view('tentang');
})->name('tentang');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pesanan', [PreorderController::class, 'adminIndex'])->name('pesanan.admin');
    Route::patch('/pesanan/{id}/status', [PreorderController::class, 'updateStatus'])->name('pesanan.status');
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::post('/produk/simpan', [ProdukController::class, 'store'])->name('produk.simpan');
    Route::get('/produk/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');
});

Route::middleware(['auth', 'role:petani'])->group(function () {
    Route::get('/petani/produk-saya', [PetaniProdukController::class, 'index'])->name('petani.produk.index');
    Route::post('/petani/produk-saya', [PetaniProdukController::class, 'store'])->name('petani.produk.store');
    Route::get('/petani/produk-saya/{id}/edit', [PetaniProdukController::class, 'edit'])->name('petani.produk.edit');
    Route::put('/petani/produk-saya/{id}', [PetaniProdukController::class, 'update'])->name('petani.produk.update');
    Route::delete('/petani/produk-saya/{id}', [PetaniProdukController::class, 'destroy'])->name('petani.produk.destroy');
    Route::get('/petani/pesanan-saya', [PetaniPesananController::class, 'index'])->name('petani.pesanan.index');
    Route::patch('/petani/pesanan-saya/{id}/konfirmasi', [PetaniPesananController::class, 'konfirmasi'])->name('petani.pesanan.konfirmasi');
});

Route::middleware(['auth', 'cek.admin'])->group(function () {
    Route::get('/petani/search', [PetaniController::class, 'search'])->name('petani.search');
    Route::resource('petani', PetaniController::class);
});

Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/produk-list', [ProdukController::class, 'indexPelanggan'])->name('produk.list');
    Route::get('/preorder', [PreorderController::class, 'create'])->name('preorder');
    Route::post('/preorder', [PreorderController::class, 'store'])->name('preorder.store');
    Route::post('/pesanan/simpan', [PreorderController::class, 'store'])->name('preorder.simpan');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');
    Route::patch('/riwayat/{id}/status', [RiwayatController::class, 'updateStatus'])->name('riwayat.status');
    Route::delete('/riwayat/{id}', [RiwayatController::class, 'hapus'])->name('riwayat.hapus');
    Route::delete('/riwayat', [RiwayatController::class, 'hapusSemua'])->name('riwayat.hapus-semua');

    Route::get('/preorder/cookie/baca', [PreorderController::class, 'bacaCookie'])->name('preorder.cookie.baca');
    Route::post('/preorder/cookie/simpan', [PreorderController::class, 'simpanCookie'])->name('preorder.cookie.simpan');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/kunjungan/reset', function () {
    session()->forget(['kunjungan_count', 'kunjungan_pertama', 'kunjungan_terakhir']);
    return redirect()->back()->with('success', 'Hitungan kunjungan direset!');
})->middleware('auth')->name('kunjungan.reset');

Route::get('/preferensi', [PreferensiController::class, 'index'])->name('preferensi');
Route::post('/preferensi/simpan', [PreferensiController::class, 'simpan'])->name('preferensi.simpan');
Route::get('/preferensi/baca', [PreferensiController::class, 'baca'])->name('preferensi.baca');
Route::delete('/preferensi/reset', [PreferensiController::class, 'reset'])->name('preferensi.reset');

require __DIR__.'/auth.php';