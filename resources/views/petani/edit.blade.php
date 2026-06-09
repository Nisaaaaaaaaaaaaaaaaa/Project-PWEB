@extends('layouts.app')

@section('title', 'Edit Petani')

@section('content')
<section class="card animate-once" style="max-width:600px;margin:0 auto">
    <h2 style="text-align:center;margin-bottom:24px">✏️ Edit Petani</h2>

    <form method="POST" action="{{ route('petani.update', $petani->id) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="form-group">
            <label for="kode">Kode Petani</label>
            <input type="text" id="kode" name="kode"
                   value="{{ old('kode', $petani->kode) }}" required>
            @error('kode')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="nama">Nama Petani</label>
            <input type="text" id="nama" name="nama"
                   value="{{ old('nama', $petani->nama) }}" required>
            @error('nama')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="telepon">Nomor Telepon / WhatsApp</label>
            <input type="tel" id="telepon" name="telepon"
                value="{{ old('telepon', $petani->telepon ?? '') }}"
                placeholder="Contoh: 08123456789">
            @error('telepon')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email', $petani->email) }}" required>
            @error('email')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="komoditas">Komoditas Utama</label>
            <select id="komoditas" name="komoditas" required>
                <option value="">-- Pilih Komoditas --</option>
                @foreach(['Padi', 'Jagung', 'Sayur', 'Buah'] as $k)
                    <option value="{{ $k }}"
                            {{ old('komoditas', $petani->komoditas) === $k ? 'selected' : '' }}>
                        {{ $k }}
                    </option>
                @endforeach
            </select>
            @error('komoditas')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="foto">Foto Profil</label>
            @if($petani->foto)
                <img src="{{ asset('storage/' . $petani->foto) }}"
                     alt="Foto {{ $petani->nama }}"
                     style="width:80px;height:80px;object-fit:cover;border-radius:50%;margin-bottom:8px;display:block">
            @endif
            <input type="file" id="foto" name="foto" accept="image/jpg,image/jpeg,image/png">
            <small style="color:#888">Kosongkan jika tidak ingin mengganti foto</small>
            @error('foto')<span class="err-msg show">{{ $message }}</span>@enderror
        </div>

        <div style="display:flex;gap:12px;margin-top:20px">
            <a href="{{ route('petani.index') }}" class="btn-kembali" style="flex:1;text-align:center">Batal</a>
            <button type="submit"
                    style="flex:2;padding:12px;background:#2f855a;color:white;border:none;
                           border-radius:40px;font-size:1rem;font-weight:700;cursor:pointer">
                💾 Update
            </button>
        </div>
    </form>
</section>
@endsection