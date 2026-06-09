<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $produkDefault = [
            [
                'nama'       => 'Padi',
                'harga'      => 6000,
                'stok'       => 500,
                'img'        => 'https://asset.kompas.com/crops/V-01zZLW6L213QV-i2W4gZ3s-Dc=/0x0:1000x667/1200x800/data/photo/2022/09/19/6327e2b3a4373.jpg',
                'is_default' => true,
            ],
            [
                'nama'       => 'Jagung',
                'harga'      => 5000,
                'stok'       => 300,
                'img'        => 'https://asset.kompas.com/crops/ZUJs6d1PvQ7H7wHI8pn_HIhRlsE=/100x67:900x600/1200x800/data/photo/2022/12/23/63a54bb4b1b41.jpg',
                'is_default' => true,
            ],
            [
                'nama'       => 'Tomat',
                'harga'      => 8000,
                'stok'       => 200,
                'img'        => 'https://asset.kompas.com/crops/RkogRiT1IP1l-BzDj77g6mmaBKs=/110x65:890x585/1200x800/data/photo/2022/08/12/62f5e998f3881.jpg',
                'is_default' => true,
            ],
            [
                'nama'       => 'Cabai',
                'harga'      => 35000,
                'stok'       => 100,
                'img'        => 'https://assets.corteva.com/is/image/Corteva/ar4-22nov21?$image_desktop$',
                'is_default' => true,
            ],
            [
                'nama'       => 'Timun',
                'harga'      => 7000,
                'stok'       => 150,
                'img'        => 'https://jagadtani.com/uploads/gallery/2020/08/panen-timun-blufarm-raup-b07388993d.JPG',
                'is_default' => true,
            ],
        ];

        foreach ($produkDefault as $data) {
            Produk::firstOrCreate(
                ['nama' => $data['nama'], 'is_default' => true],
                $data
            );
        }

        $this->command->info('✅ ProdukSeeder: ' . count($produkDefault) . ' produk default berhasil di-seed.');
    }
}