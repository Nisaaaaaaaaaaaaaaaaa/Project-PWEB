@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<section class="card animate-once" style="max-width:600px;margin:0 auto">
    <h2 style="text-align:center;margin-bottom:24px">✏️ Edit Produk</h2>

    <form method="POST" action="{{ route('petani.produk.update', $produk->id) }}">
        @csrf @method('PUT')

        <div class="form-group">
            <label for="nama">Nama Produk</label>
            <input type="text" id="nama" name="nama"
                   value="{{ old('nama', $produk->nama) }}" required>
            @error('nama')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="harga">Harga per kg (Rp)</label>
            <input type="number" id="harga" name="harga"
                   value="{{ old('harga', $produk->harga) }}" min="1" required>
            @error('harga')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="stok">Stok (kg)</label>
            <input type="number" id="stok" name="stok"
                   value="{{ old('stok', $produk->stok) }}" min="0" required>
            @error('stok')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="img">URL Gambar</label>
            <input type="url" id="img" name="img"
                   value="{{ old('img', $produk->img) }}"
                   placeholder="https://...">
            @error('img')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div style="display:flex;gap:12px;margin-top:20px">
            <a href="{{ route('petani.produk.index') }}" class="btn-kembali"
               style="flex:1;text-align:center">Batal</a>
            <button type="submit"
                    style="flex:2;padding:12px;background:#2f855a;color:white;border:none;
                           border-radius:40px;font-size:1rem;font-weight:700;cursor:pointer">
                💾 Update
            </button>
        </div>
    </form>
</section>
@endsection