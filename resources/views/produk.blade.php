@extends('layouts.app')

@section('title', 'Produk')
@section('description', 'Daftar produk hasil panen yang tersedia untuk di-pre-order.')

@section('content')

  <section aria-labelledby="judulProduk" class="card animate-once">
    <h2 id="judulProduk">Daftar Produk Hasil Panen</h2>

    <div style="text-align:right;margin-bottom:16px">
      <button class="btn-tambah-produk" id="btnTambahProduk">＋ Tambah Produk</button>
    </div>

    <div class="table-wrapper">
      <table aria-label="Tabel daftar produk">
        <thead>
          <tr>
            <th scope="col">Produk</th>
            <th scope="col">Gambar</th>
            <th scope="col">Stok</th>
            <th scope="col">Harga/kg</th>
            <th scope="col">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($produk as $p)
            @php
              $habis      = $p->stok <= 0;
              $menipis    = !$habis && $p->stok <= 20;
              $badgeClass = $habis ? 'habis' : ($menipis ? 'warn' : 'ok');
              $badgeLabel = $habis ? 'Habis' : $p->stok . ' kg';
              $isCustom   = !in_array($p->id, $defaultIds);
            @endphp
            <tr>
              <td><strong>{{ $p->nama }}</strong></td>
              <td>
                @if($p->img)
                  <img src="{{ $p->img }}" alt="Foto {{ $p->nama }}"
                       loading="lazy"
                       style="width:60px;height:45px;object-fit:cover;border-radius:8px;">
                @else
                  <span style="color:#aaa;font-size:.85rem">—</span>
                @endif
              </td>
              <td>
                <span class="stok-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
              </td>
              <td>Rp {{ number_format($p->harga, 0, ',', '.') }}/kg</td>
              <td style="display:flex;gap:6px;justify-content:center;align-items:center;flex-wrap:wrap">
                @if($habis)
                  <span class="btn-pesan disabled" aria-disabled="true">Stok Habis</span>
                @else
                  <a href="{{ route('preorder') }}?produk={{ $p->id }}" class="btn-pesan">Pesan</a>
                @endif

                <a href="{{ route('produk.edit', $p->id) }}" class="btn-edit-produk">✏️ Edit</a>

                @if($isCustom)
                  <form method="POST" action="{{ route('produk.destroy', $p->id) }}"
                        onsubmit="return confirm('Hapus produk {{ $p->nama }}?')"
                        style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-hapus">🗑</button>
                  </form>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align:center;color:#888;padding:24px">
                Belum ada produk. Tambahkan produk pertama kamu!
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  <dialog id="modalProduk" aria-labelledby="modalProdukJudul">
    <div class="modal-backdrop" id="modalProdukBackdrop"></div>
    <div class="modal-box" style="max-width:500px;width:94%;text-align:left">
      <h2 id="modalProdukJudul" style="text-align:center;margin-bottom:20px">➕ Tambah Produk</h2>

      <form method="POST" action="{{ route('produk.simpan') }}" novalidate>
        @csrf
        <div class="form-group">
          <label for="mpNama">Nama Produk</label>
          <input type="text" id="mpNama" name="nama"
                 value="{{ old('nama') }}"
                 placeholder="Contoh: Singkong" autocomplete="off" required>
          @error('nama')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
          <label for="mpHarga">Harga per kg (Rp)</label>
          <input type="number" id="mpHarga" name="harga"
                 value="{{ old('harga') }}"
                 placeholder="Contoh: 5000" min="1" required>
          @error('harga')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
          <label for="mpStok">Stok Awal (kg)</label>
          <input type="number" id="mpStok" name="stok"
                 value="{{ old('stok') }}"
                 placeholder="Contoh: 200" min="0" required>
          @error('stok')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
          <label for="mpImg">URL Gambar Produk</label>
          <input type="url" id="mpImg" name="img"
                 value="{{ old('img') }}"
                 placeholder="https://...">
          @error('img')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>
        <div style="display:flex;gap:12px;margin-top:20px">
          <button type="button" class="btn-kembali" id="btnBatalProduk"
                  style="flex:1;text-align:center">Batal</button>
          <button type="submit"
                  style="flex:2;padding:12px;background:#2f855a;color:white;border:none;
                         border-radius:40px;font-size:1rem;font-weight:700;cursor:pointer;
                         transition:all .2s">
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
  console.log('Halaman produk akan dimuat!');
  const modalProduk = document.getElementById('modalProduk');
  document.getElementById('btnTambahProduk')
    ?.addEventListener('click', () => modalProduk?.showModal());
  document.getElementById('btnBatalProduk')
    ?.addEventListener('click', () => modalProduk?.close());
  document.getElementById('modalProdukBackdrop')
    ?.addEventListener('click', () => modalProduk?.close());

  @if($errors->any())
    document.addEventListener('DOMContentLoaded', () => modalProduk?.showModal());
  @endif
</script>
@endpush