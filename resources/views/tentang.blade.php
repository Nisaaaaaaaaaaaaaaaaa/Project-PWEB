@extends('layouts.app')

@section('title', 'Tentang')
@section('description', 'Tentang platform PanenKu dan teknologi yang digunakan.')

@section('content')

  <article aria-labelledby="judulTentang" class="card animate-once">
    <h2 id="judulTentang">Tentang PanenKu 🌾</h2>

    <p>
      <strong>PanenKu</strong> adalah platform digital pre-order hasil panen yang
      menghubungkan petani lokal langsung dengan pembeli. Kami hadir untuk
      mempermudah proses jual-beli produk pertanian secara transparan dan efisien.
    </p>

    <br>

    <p>
      Dibangun menggunakan <strong>Laravel {{ app()->version() }}</strong> sebagai
      framework backend, dirancang untuk memudahkan pengelolaan stok, pesanan,
      dan riwayat transaksi.
    </p>

    <ul class="features" style="margin-top:20px">
      <li>🌾 Produk langsung dari petani lokal</li>
      <li>💰 Harga lebih transparan &amp; terjangkau</li>
      <li>📦 Sistem pre-order yang mudah &amp; cepat</li>
      <li>📊 Riwayat pesanan tersimpan otomatis</li>
      <li>🔒 Data aman &amp; terkelola dengan baik</li>
      <li>🗄️ Database MySQL via Eloquent ORM</li>
    </ul>
  </article>

  <section aria-labelledby="judulTeknis" class="card animate-once" style="animation-delay:.1s">
    <h2 id="judulTeknis">🛠 Teknologi yang Digunakan</h2>
    <ul class="features">
      <li>⚙️ <strong>Backend:</strong> Laravel {{ app()->version() }} (PHP)</li>
      <li>🗄️ <strong>Database:</strong> MySQL + Eloquent ORM</li>
      <li>🎨 <strong>Frontend:</strong> HTML5 / CSS3 / Vanilla JS</li>
      <li>🧩 <strong>Template Engine:</strong> Blade (Layout Master + Partial)</li>
      <li>🔗 <strong>Routing:</strong> Laravel Named Routes</li>
    </ul>
  </section>

  <div class="button-center">
    <a href="{{ route('beranda') }}" class="btn-kembali">← Kembali ke Beranda</a>
  </div>

@endsection