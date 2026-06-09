@extends('layouts.app')

@section('title', 'Kelola Pesanan')
@section('description', 'Kelola semua pesanan pre-order masuk.')

@section('content')

  <section aria-labelledby="judulPesanan" class="card animate-once">
    <h2 id="judulPesanan" class="judul-riwayat">Kelola Pesanan 📋</h2>

    <nav class="filter-bar" aria-label="Filter status pesanan">
      <a href="{{ route('pesanan.admin') }}"
         class="filter-btn {{ !request('status') ? 'active' : '' }}">
        Semua
      </a>
      <a href="{{ route('pesanan.admin', ['status' => 'Menunggu']) }}"
         class="filter-btn {{ request('status') === 'Menunggu' ? 'active' : '' }}">
        ⏳ Menunggu
      </a>
      <a href="{{ route('pesanan.admin', ['status' => 'Diproses']) }}"
         class="filter-btn {{ request('status') === 'Diproses' ? 'active' : '' }}">
        🔄 Diproses
      </a>
      <a href="{{ route('pesanan.admin', ['status' => 'Selesai']) }}"
         class="filter-btn {{ request('status') === 'Selesai' ? 'active' : '' }}">
        ✅ Selesai
      </a>
    </nav>

    @if($preorders->count())
      <div class="table-wrapper">
        <table aria-label="Tabel kelola pesanan">
          <thead>
            <tr>
              <th scope="col">Tanggal</th>
              <th scope="col">Nama Pembeli</th>
              <th scope="col">Telepon</th>
              <th scope="col">Produk</th>
              <th scope="col">Jumlah</th>
              <th scope="col">Total</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody aria-live="polite">
            @forelse($preorders as $o)
              @php
                $sc = match($o->status) {
                  'Menunggu' => 'status-menunggu',
                  'Diproses' => 'status-diproses',
                  'Selesai'  => 'status-selesai',
                  default    => '',
                };
              @endphp
              <tr>
                <td>{{ $o->tanggal ?? '-' }}</td>
                <td><strong>{{ $o->nama }}</strong></td>
                <td>{{ $o->telepon ?? '-' }}</td>
                <td>{{ $o->produk_nama }}</td>
                <td>{{ $o->jumlah }} kg</td>
                <td>Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                <td>
                  <form method="POST" action="{{ route('pesanan.status', $o->id) }}"
                        style="margin:0">
                    @csrf @method('PATCH')
                    <select name="status"
                            class="status-select {{ $sc }}"
                            onchange="this.form.submit()"
                            aria-label="Status pesanan {{ $o->nama }}">
                      <option value="Menunggu" {{ $o->status === 'Menunggu' ? 'selected' : '' }}>
                        ⏳ Menunggu
                      </option>
                      <option value="Diproses" {{ $o->status === 'Diproses' ? 'selected' : '' }}>
                        🔄 Diproses
                      </option>
                      <option value="Selesai" {{ $o->status === 'Selesai' ? 'selected' : '' }}>
                        ✅ Selesai
                      </option>
                    </select>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" style="text-align:center;color:#888;padding:24px">
                  Tidak ada pesanan dengan filter ini.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    @else
      <section style="text-align:center;padding:40px 20px"
               aria-label="Tidak ada pesanan">
        <p style="font-size:3rem;margin-bottom:12px" aria-hidden="true">📭</p>
        <p style="color:#888;font-size:1rem">Belum ada pesanan masuk.</p>
      </section>
    @endif
  </section>

  <div class="button-center">
    <a href="{{ route('beranda') }}" class="btn-kembali">← Kembali ke Beranda</a>
  </div>

@endsection