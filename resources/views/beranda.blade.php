@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

  <article aria-labelledby="judulSambutan" class="card animate-once">
    <h2 id="judulSambutan">Selamat Datang di PanenKu 🌾</h2>
    <p>
      Website ini membantu pembeli melakukan pemesanan hasil panen secara
      pre-order langsung dari petani. Sistem ini memudahkan pengguna untuk
      melihat produk yang tersedia, melakukan pemesanan, serta memantau
      riwayat transaksi dengan lebih praktis.
    </p>
  </article>

  <section aria-labelledby="judulUnggulan" class="card animate-once" style="animation-delay:.1s">
    <h2 id="judulUnggulan">Keunggulan Platform</h2>
    <ul class="features">
      <li>🌾 Produk langsung dari petani lokal</li>
      <li>💰 Harga lebih transparan &amp; terjangkau</li>
      <li>📦 Sistem pre-order yang mudah &amp; cepat</li>
      <li>📊 Riwayat pesanan tersimpan otomatis</li>
    </ul>
  </section>

  <section aria-label="Statistik pesanan" class="animate-once" style="animation-delay:.2s">
    <div class="stats-row" role="list">
        <div class="stat-card" role="listitem">
            <div class="stat-num">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Pesanan</div>
        </div>
        <div class="stat-card" role="listitem">
            <div class="stat-num stat-menunggu">{{ $stats['menunggu'] }}</div>
            <div class="stat-label">Menunggu</div>
        </div>
        <div class="stat-card" role="listitem">
            <div class="stat-num stat-diproses">{{ $stats['diproses'] }}</div>
            <div class="stat-label">Diproses</div>
        </div>
        <div class="stat-card" role="listitem">
            <div class="stat-num stat-selesai">{{ $stats['selesai'] }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>
</section>

  <section class="card animate-once" id="cuacaSection" style="margin-top:24px">    <h2>🌤 Cuaca Hari Ini</h2>
    <div id="cuacaLoading" style="text-align:center;padding:20px;color:#888">
        ⏳ Memuat data cuaca...
    </div>
    <div id="cuacaData" style="display:none">
        <p>📍 Kota: <strong id="cuacaKota"></strong></p>
        <p>🌡 Suhu: <strong id="cuacaSuhu"></strong>°C</p>
        <p>🌥 Kondisi: <strong id="cuacaDeskripsi"></strong></p>
    </div>
    <div id="cuacaError" style="display:none;color:red">
        ❌ Gagal memuat data cuaca.
    </div>
</section>

  <section class="card animate-once">
    <h2>📊 Statistik Kunjungan</h2>
    <p>🔢 Jumlah kunjungan: <strong>{{ $kunjungan_count }}</strong></p>
    <p>🕐 Kunjungan pertama: <strong>{{ $kunjungan_pertama ?? '-' }}</strong></p>
    <p>🕐 Kunjungan terakhir: <strong>{{ $kunjungan_terakhir }}</strong></p>

    <form method="POST" action="{{ route('kunjungan.reset') }}" style="margin-top:12px">
        @csrf
        <button type="submit" class="btn-hapus"
                onclick="return confirm('Reset hitungan kunjungan?')">
            🔄 Reset Hitungan
        </button>
    </form>
</section>

@endsection

@push('scripts')
<script>
async function ambilCuaca() {
    const loading = document.getElementById('cuacaLoading');
    const dataDiv = document.getElementById('cuacaData');
    const errorDiv = document.getElementById('cuacaError');

    try {
        const response = await fetch(
            'https://api.open-meteo.com/v1/forecast?latitude=-8.172&longitude=113.700&current=temperature_2m,weathercode&timezone=Asia%2FJakarta'
        );
        const data = await response.json();

        const suhu = data.current.temperature_2m;
        const kode = data.current.weathercode;

        const deskripsi = {
            0: 'Cerah', 1: 'Sebagian Cerah', 2: 'Berawan', 3: 'Mendung',
            45: 'Berkabut', 48: 'Berkabut Tebal',
            51: 'Gerimis Ringan', 53: 'Gerimis', 55: 'Gerimis Lebat',
            61: 'Hujan Ringan', 63: 'Hujan', 65: 'Hujan Lebat',
            80: 'Hujan Lokal', 81: 'Hujan Deras', 82: 'Hujan Sangat Deras',
            95: 'Badai Petir', 99: 'Badai Petir Lebat',
        }[kode] ?? 'Tidak Diketahui';

        document.getElementById('cuacaKota').textContent = 'Jember';
        document.getElementById('cuacaSuhu').textContent = suhu;
        document.getElementById('cuacaDeskripsi').textContent = deskripsi;

        loading.style.display = 'none';
        dataDiv.style.display = 'block';
    } catch (e) {
        console.error('Cuaca error:', e);
        loading.style.display = 'none';
        errorDiv.style.display = 'block';
    }
}

ambilCuaca();
</script>
@endpush