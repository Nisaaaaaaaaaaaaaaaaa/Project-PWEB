@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')

  <section class="card animate-once" style="max-width:520px;margin:0 auto">
    <h2>✏️ Edit Produk</h2>

    <form method="POST" action="{{ route('produk.update', $produk->id) }}">
      @csrf @method('PUT')

      <div class="form-group">
        <label for="nama">Nama Produk</label>
        <input type="text" id="nama" name="nama"
               value="{{ old('nama', $produk->nama) }}"
               placeholder="Contoh: Singkong" required>
        @error('nama')<span class="err-msg show">{{ $message }}</span>@enderror
      </div>

      <div class="form-group">
        <label for="harga">Harga per kg (Rp)</label>
        <input type="number" id="harga" name="harga"
               value="{{ old('harga', $produk->harga) }}"
               placeholder="Contoh: 5000" min="1" required>
        @error('harga')<span class="err-msg show">{{ $message }}</span>@enderror
      </div>

      <div class="form-group">
        <label for="stok">Stok (kg)</label>
        <input type="number" id="stok" name="stok"
               value="{{ old('stok', $produk->stok) }}"
               placeholder="Contoh: 200" min="0" required>
        @error('stok')<span class="err-msg show">{{ $message }}</span>@enderror
      </div>

      <div class="form-group">
        <label for="img">URL Gambar Produk</label>
        <input type="url" id="img" name="img"
               value="{{ old('img', $produk->img) }}"
               placeholder="https://...">
        @error('img')<span class="err-msg show">{{ $message }}</span>@enderror
        @if($produk->img)
          <img src="{{ $produk->img }}" alt="Preview"
               style="margin-top:10px;width:120px;height:90px;
                      object-fit:cover;border-radius:10px;">
        @endif
      </div>

      <div style="display:flex;gap:12px;margin-top:24px">
        <a href="{{ route('produk') }}" class="btn-kembali"
           style="flex:1;text-align:center;display:flex;align-items:center;justify-content:center">
          Batal
        </a>
        <button type="submit"
                style="flex:2;padding:12px;background:#2f855a;color:white;border:none;
                       border-radius:40px;font-size:1rem;font-weight:700;cursor:pointer">
          💾 Simpan Perubahan
        </button>
      </div>
    </form>
  </section>

@endsection