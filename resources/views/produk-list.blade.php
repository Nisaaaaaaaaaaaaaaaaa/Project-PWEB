@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')

  <section class="card animate-once">
    <h2>Produk Tersedia 🌾</h2>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Produk</th>
            <th>Petani</th>
            <th>Gambar</th>
            <th>Stok</th>
            <th>Harga/kg</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($produk as $p)
            @php
              $habis      = $p->stok <= 0;
              $menipis    = !$habis && $p->stok <= 20;
              $badgeClass = $habis ? 'habis' : ($menipis ? 'warn' : 'ok');
              $badgeLabel = $habis ? 'Habis' : $p->stok . ' kg';
            @endphp
            <tr>
              <td><strong>{{ $p->nama }}</strong></td>
              <td>{{ $p->user->name ?? '-' }}</td>
              <td>
                @if($p->img)
                  <img src="{{ $p->img }}" alt="Foto {{ $p->nama }}"
                       loading="lazy"
                       style="width:60px;height:45px;object-fit:cover;border-radius:8px;">
                @else
                  <span style="color:#aaa">—</span>
                @endif
              </td>
              <td><span class="stok-badge {{ $badgeClass }}">{{ $badgeLabel }}</span></td>
              <td>Rp {{ number_format($p->harga, 0, ',', '.') }}/kg</td>
              <td>
                @if($habis)
                  <span class="btn-pesan disabled">Stok Habis</span>
                @else
                  <a href="{{ route('preorder') }}?produk={{ $p->id }}" class="btn-pesan">Pesan</a>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align:center;color:#888;padding:24px">
                Belum ada produk tersedia saat ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  <div class="button-center">
    <a href="{{ route('beranda') }}" class="btn-kembali">← Kembali ke Beranda</a>
  </div>

@endsection