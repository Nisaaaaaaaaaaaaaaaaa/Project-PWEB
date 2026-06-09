@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<section class="card animate-once">
    <h2>📋 Pesanan Produk Saya</h2>

    @if($pesanan->count())
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Pembeli</th>
                        <th>Telepon</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanan as $o)
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
                                <form method="POST"
                                      action="{{ route('petani.pesanan.konfirmasi', $o->id) }}"
                                      style="margin:0">
                                    @csrf @method('PATCH')
                                    <select name="status"
                                            class="status-select {{ $sc }}"
                                            onchange="this.form.submit()">
                                        <option value="Menunggu" {{ $o->status === 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                                        <option value="Diproses" {{ $o->status === 'Diproses' ? 'selected' : '' }}>🔄 Diproses</option>
                                        <option value="Selesai"  {{ $o->status === 'Selesai'  ? 'selected' : '' }}>✅ Selesai</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align:center;padding:40px">
            <p style="font-size:3rem">📭</p>
            <p style="color:#888">Belum ada pesanan untuk produk kamu.</p>
        </div>
    @endif
</section>

<div class="button-center">
    <a href="{{ route('beranda') }}" class="btn-kembali">← Kembali ke Beranda</a>
</div>
@endsection