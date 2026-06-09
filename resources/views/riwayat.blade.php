@extends('layouts.app')

@section('title', 'Riwayat')
@section('description', 'Riwayat pesanan pre-order produk hasil panen.')

@section('content')

  <section aria-labelledby="judulRiwayat" class="card animate-once">
    <h2 id="judulRiwayat" class="judul-riwayat">Riwayat Pre-Order 📋</h2>

    @if($semua->count())
      <nav class="filter-bar" aria-label="Filter status pesanan">
        <a href="{{ route('riwayat') }}"
           class="filter-btn {{ !request('status') ? 'active' : '' }}">
          Semua
        </a>
        <a href="{{ route('riwayat', ['status' => 'Menunggu']) }}"
           class="filter-btn {{ request('status') === 'Menunggu' ? 'active' : '' }}">
          ⏳ Menunggu
        </a>
        <a href="{{ route('riwayat', ['status' => 'Diproses']) }}"
           class="filter-btn {{ request('status') === 'Diproses' ? 'active' : '' }}">
          🔄 Diproses
        </a>
        <a href="{{ route('riwayat', ['status' => 'Selesai']) }}"
           class="filter-btn {{ request('status') === 'Selesai' ? 'active' : '' }}">
          ✅ Selesai
        </a>
      </nav>
    @endif

    @if($semua->count())
      <div class="table-wrapper">
        <table id="tabelRiwayat" aria-label="Tabel riwayat pesanan">
          <thead>
            <tr>
              <th scope="col">Tanggal</th>
              <th scope="col">Nama Pembeli</th>
              <th scope="col">Produk</th>
              <th scope="col">Jumlah</th>
              <th scope="col">Total</th>
              <th scope="col">Status</th>
              <th scope="col">Aksi</th>
            </tr>
          </thead>
          <tbody aria-live="polite">
            @forelse($semua as $o)
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
                <td>{{ $o->produk_nama }}</td>
                <td>{{ $o->jumlah }} kg</td>
                <td>Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                <td>
                  <form method="POST" action="{{ route('riwayat.status', $o->id) }}"
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
                      <option value="Selesai"  {{ $o->status === 'Selesai'  ? 'selected' : '' }}>
                        ✅ Selesai
                      </option>
                    </select>
                  </form>
                </td>
                <td>
                  <form method="POST" action="{{ route('riwayat.hapus', $o->id) }}"
                        onsubmit="return confirm('Hapus pesanan {{ $o->nama }}?')"
                        style="margin:0">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-hapus">Hapus</button>
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
               aria-label="Tidak ada riwayat pesanan">
        <p style="font-size:3rem;margin-bottom:12px" aria-hidden="true">📭</p>
        <p style="color:#888;font-size:1rem">Belum ada riwayat pesanan.</p>
        <a href="{{ route('preorder') }}" class="btn-kembali"
           style="display:inline-block;margin-top:16px">
          + Buat Pesanan
        </a>
      </section>
    @endif
  </section>

  @if($semua->count())
    <div class="button-center">
      <form method="POST" action="{{ route('riwayat.hapus-semua') }}"
            onsubmit="return confirm('Hapus SEMUA riwayat pesanan? Tindakan ini tidak bisa dibatalkan.')">
        @csrf @method('DELETE')
        <button type="submit" class="btn-hapus-semua">🗑 Hapus Semua Riwayat</button>
      </form>
    </div>
  @endif

  <div class="button-center">
    <a href="{{ route('beranda') }}" class="btn-kembali">← Kembali ke Beranda</a>
  </div>

@endsection