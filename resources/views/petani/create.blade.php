@extends('layouts.app')

@section('title', 'Tambah Petani')

@section('content')
<section class="card animate-once" style="max-width:600px;margin:0 auto">
    <h2 style="text-align:center;margin-bottom:24px">🌱 Tambah Petani</h2>

    <form method="POST" action="{{ route('petani.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="kode">Kode Petani</label>
            <input type="text" id="kode" name="kode"
                   value="{{ old('kode') }}"
                   placeholder="Contoh: PTN001" required>
            @error('kode')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="nama">Nama Petani</label>
            <input type="text" id="nama" name="nama"
                   value="{{ old('nama') }}"
                   placeholder="Nama lengkap petani" required>
            @error('nama')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="email@contoh.com" required>
            @error('email')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="komoditas">Komoditas Utama</label>
            <select id="komoditas" name="komoditas" required>
                <option value="">-- Pilih Komoditas --</option>
                @foreach(['Padi', 'Jagung', 'Sayur', 'Buah'] as $k)
                    <option value="{{ $k }}" {{ old('komoditas') === $k ? 'selected' : '' }}>
                        {{ $k }}
                    </option>
                @endforeach
            </select>
            @error('komoditas')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="foto">Foto Profil</label>
            <input type="file" id="foto" name="foto" accept="image/jpg,image/jpeg,image/png">
            @error('foto')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="telepon">Nomor Telepon / WhatsApp</label>
            <input type="tel" id="telepon" name="telepon"
                value="{{ old('telepon', $petani->telepon ?? '') }}"
                placeholder="Contoh: 08123456789">
            @error('telepon')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div style="display:flex;gap:12px;margin-top:20px">
            <a href="{{ route('petani.index') }}" class="btn-kembali" style="flex:1;text-align:center">Batal</a>
            <button type="submit"
                    style="flex:2;padding:12px;background:#2f855a;color:white;border:none;
                           border-radius:40px;font-size:1rem;font-weight:700;cursor:pointer">
                💾 Simpan
            </button>
        </div>
    </form>
</section>
@endsection