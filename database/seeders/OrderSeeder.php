<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Produk;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $padi   = Produk::where('nama', 'Padi')->first();
        $jagung = Produk::where('nama', 'Jagung')->first();
        $tomat  = Produk::where('nama', 'Tomat')->first();
        $cabai  = Produk::where('nama', 'Cabai')->first();
        $timun  = Produk::where('nama', 'Timun')->first();

        if (!$padi) {
            $this->command->warn('⚠ Jalankan ProdukSeeder dulu sebelum OrderSeeder.');
            return;
        }

        $orders = [
            [
                'nama'        => 'Budi Santoso',
                'telepon'     => '081234567890',
                'alamat'      => 'Jl. Merdeka No. 12, Situbondo',
                'produk_id'   => $padi->id,
                'produk_nama' => $padi->nama,
                'jumlah'      => 50,
                'total'       => $padi->harga * 50,
                'status'      => 'Selesai',
                'tanggal'     => '01 Mei 2026',
            ],
            [
                'nama'        => 'Siti Rahayu',
                'telepon'     => '082345678901',
                'alamat'      => 'Desa Sumberejo RT 02/03, Bondowoso',
                'produk_id'   => $jagung->id,
                'produk_nama' => $jagung->nama,
                'jumlah'      => 30,
                'total'       => $jagung->harga * 30,
                'status'      => 'Selesai',
                'tanggal'     => '02 Mei 2026',
            ],
            [
                'nama'        => 'Ahmad Fauzi',
                'telepon'     => '083456789012',
                'alamat'      => 'Jl. Diponegoro No. 5, Banyuwangi',
                'produk_id'   => $tomat->id,
                'produk_nama' => $tomat->nama,
                'jumlah'      => 20,
                'total'       => $tomat->harga * 20,
                'status'      => 'Diproses',
                'tanggal'     => '02 Mei 2026',
            ],
            [
                'nama'        => 'Dewi Lestari',
                'telepon'     => '084567890123',
                'alamat'      => 'Perum Griya Indah Blok C No. 8, Jember',
                'produk_id'   => $cabai->id,
                'produk_nama' => $cabai->nama,
                'jumlah'      => 10,
                'total'       => $cabai->harga * 10,
                'status'      => 'Diproses',
                'tanggal'     => '03 Mei 2026',
            ],
            [
                'nama'        => 'Rudi Hartono',
                'telepon'     => '085678901234',
                'alamat'      => 'Jl. Raya Banyuwangi Km 3, Genteng',
                'produk_id'   => $timun->id,
                'produk_nama' => $timun->nama,
                'jumlah'      => 25,
                'total'       => $timun->harga * 25,
                'status'      => 'Menunggu',
                'tanggal'     => '03 Mei 2026',
            ],
            [
                'nama'        => 'Ningsih Wulandari',
                'telepon'     => '086789012345',
                'alamat'      => 'Dsn. Sumber Agung, Lumajang',
                'produk_id'   => $padi->id,
                'produk_nama' => $padi->nama,
                'jumlah'      => 100,
                'total'       => $padi->harga * 100,
                'status'      => 'Menunggu',
                'tanggal'     => '03 Mei 2026',
            ],
            [
                'nama'        => 'Hendra Wijaya',
                'telepon'     => '087890123456',
                'alamat'      => 'Jl. Ahmad Yani No. 22, Probolinggo',
                'produk_id'   => $jagung->id,
                'produk_nama' => $jagung->nama,
                'jumlah'      => 15,
                'total'       => $jagung->harga * 15,
                'status'      => 'Selesai',
                'tanggal'     => '01 Mei 2026',
            ],
            [
                'nama'        => 'Yuli Astuti',
                'telepon'     => '088901234567',
                'alamat'      => 'Perum Puri Asri No. 11, Pasuruan',
                'produk_id'   => $tomat->id,
                'produk_nama' => $tomat->nama,
                'jumlah'      => 8,
                'total'       => $tomat->harga * 8,
                'status'      => 'Menunggu',
                'tanggal'     => '03 Mei 2026',
            ],
        ];

        foreach ($orders as $o) {
            Order::create($o);
        }

        $this->command->info('✅ OrderSeeder: ' . count($orders) . ' pesanan dummy berhasil di-seed.');
    }
}