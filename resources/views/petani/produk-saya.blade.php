@extends('layouts.app')

@section('title', 'Produk Saya')

@section('content')
<section class="card animate-once">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <h2>🌾 Produk Saya</h2>
        <button class="btn-tambah-produk" id="btnTambahProduk">+ Tambah Produk</button>
    </div>

    <div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Nama</th>
                <th>Stok</th>
                <th>Harga/kg</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produk as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($p->img)
                            <img src="{{ $p->img }}" alt="{{ $p->nama }}"
                                 style="width:60px;height:45px;object-fit:cover;border-radius:8px">
                        @else
                            <span style="color:#aaa">—</span>
                        @endif
                    </td>
                    <td><strong>{{ $p->nama }}</strong></td>
                    <td>
                        <span class="stok-badge {{ $p->stok <= 0 ? 'habis' : ($p->stok <= 20 ? 'warn' : 'ok') }}">
                            {{ $p->stok <= 0 ? 'Habis' : $p->stok . ' kg' }}
                        </span>
                    </td>
                    <td>Rp {{ number_format($p->harga, 0, ',', '.') }}/kg</td>
                    <td style="display:flex;gap:6px;justify-content:center">
                        <a href="{{ route('petani.produk.edit', $p->id) }}" class="btn-edit-produk">✏️ Edit</a>
                        <form method="POST" action="{{ route('petani.produk.destroy', $p->id) }}"
                              onsubmit="return confirm('Hapus produk {{ $p->nama }}?')"
                              style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-hapus">🗑</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:#888;padding:24px">
                        Belum ada produk. Tambahkan produk pertama kamu!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    <div style="margin-top:16px">{{ $produk->links() }}</div>
</section>

<dialog id="modalProduk" aria-labelledby="modalProdukJudul">
    <div class="modal-box" style="max-width:500px;width:94%;text-align:left">
        <h2 id="modalProdukJudul" style="text-align:center;margin-bottom:20px">➕ Tambah Produk</h2>
        <form method="POST" action="{{ route('petani.produk.store') }}" novalidate>
            @csrf
            <div class="form-group">
                <label for="nama">Nama Produk</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                       placeholder="Contoh: Padi Organik" required>
                @error('nama')<span class="err-msg show">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="harga">Harga per kg (Rp)</label>
                <input type="number" id="harga" name="harga" value="{{ old('harga') }}"
                       placeholder="Contoh: 5000" min="1" required>
                @error('harga')<span class="err-msg show">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="stok">Stok (kg)</label>
                <input type="number" id="stok" name="stok" value="{{ old('stok') }}"
                       placeholder="Contoh: 100" min="0" required>
                @error('stok')<span class="err-msg show">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="img">URL Gambar</label>
                <input type="url" id="img" name="img" value="{{ old('img') }}"
                       placeholder="https://...">
                @error('img')<span class="err-msg show">{{ $message }}</span>@enderror
            </div>
            <div style="display:flex;gap:12px;margin-top:20px">
                <button type="button" class="btn-kembali" id="btnBatal"
                        style="flex:1;text-align:center">Batal</button>
                <button type="submit"
                        style="flex:2;padding:12px;background:#2f855a;color:white;border:none;
                               border-radius:40px;font-size:1rem;font-weight:700;cursor:pointer">
                    💾 Simpan
                </button>
            </div>
        </form>
    </div>
</dialog>

<div class="button-center">
    <a href="{{ route('beranda') }}" class="btn-kembali">← Kembali ke Beranda</a>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('modalProduk');
    document.getElementById('btnTambahProduk')?.addEventListener('click', () => modal?.showModal());
    document.getElementById('btnBatal')?.addEventListener('click', () => modal?.close());
    @if($errors->any()) document.addEventListener('DOMContentLoaded', () => modal?.showModal()); @endif
</script>
@endpush