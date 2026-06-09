@extends('layouts.app')

@section('title', 'Detail Petani')

@section('content')
<section class="card animate-once" style="max-width:600px;margin:0 auto">
    <h2 style="text-align:center;margin-bottom:24px">👁 Detail Petani</h2>

    <div style="text-align:center;margin-bottom:24px">
        @if($petani->foto)
            <img src="{{ asset('storage/' . $petani->foto) }}"
                 alt="Foto {{ $petani->nama }}"
                 style="width:120px;height:120px;object-fit:cover;border-radius:50%;border:4px solid #2f855a">
        @else
            <div style="width:120px;height:120px;border-radius:50%;background:#e2e8f0;
                        display:flex;align-items:center;justify-content:center;
                        font-size:3rem;margin:0 auto">🧑‍🌾</div>
        @endif
    </div>

    <table style="width:100%;border-collapse:collapse">
        <tr style="border-bottom:1px solid #e2e8f0">
            <td style="padding:12px;color:#666;width:40%">Kode Petani</td>
            <td style="padding:12px;font-weight:700">{{ $petani->kode }}</td>
        </tr>
        <tr style="border-bottom:1px solid #e2e8f0">
            <td style="padding:12px;color:#666">Nama</td>
            <td style="padding:12px;font-weight:700">{{ $petani->nama }}</td>
        </tr>
        <tr style="border-bottom:1px solid #e2e8f0">
            <td style="padding:12px;color:#666">Email</td>
            <td style="padding:12px">{{ $petani->email }}</td>
        </tr>
        <tr style="border-bottom:1px solid #e2e8f0">
            <td style="padding:12px;color:#666">Komoditas</td>
            <td style="padding:12px">
                <span class="stok-badge ok">{{ $petani->komoditas }}</span>
            </td>
        </tr>
        <tr style="border-bottom:1px solid #e2e8f0">
            <td style="padding:12px;color:#666">Telepon</td>
            <td style="padding:12px">{{ $petani->telepon ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding:12px;color:#666">Terdaftar</td>
            <td style="padding:12px">{{ $petani->created_at->format('d M Y') }}</td>
        </tr>
    </table>

    <div style="display:flex;gap:12px;margin-top:24px">
        <a href="{{ route('petani.index') }}" class="btn-kembali" style="flex:1;text-align:center">← Kembali</a>
        <a href="{{ route('petani.edit', $petani->id) }}"
           style="flex:1;text-align:center;padding:12px;background:#2f855a;color:white;
                  border-radius:40px;font-weight:700;text-decoration:none">
            ✏️ Edit
        </a>
    </div>
</section>
@endsection