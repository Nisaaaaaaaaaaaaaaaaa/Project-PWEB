@extends('layouts.app')

@section('title', 'Pre Order')
@section('description', 'Form pre-order hasil panen langsung dari petani.')

@section('content')

  <section aria-labelledby="judulPreorder" class="card animate-once">
    <h2 id="judulPreorder">📦 Form Pre-Order</h2>

    <form id="formPreorder" method="POST" action="{{ route('preorder.simpan') }}" novalidate>
      @csrf

      <div class="form-group">
        <label for="nama">Nama Pembeli</label>
        <input type="text" id="nama" name="nama"
               value="{{ old('nama') }}"
               placeholder="Nama lengkap pembeli" required>
        @error('nama')
          <span class="err-msg show">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label for="telepon">Nomor Telepon / WhatsApp</label>
        <input type="tel" id="telepon" name="telepon"
               value="{{ old('telepon') }}"
               placeholder="Contoh: 08123456789" required>
        @error('telepon')
          <span class="err-msg show">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label for="alamat">Alamat Pengiriman</label>
        <textarea id="alamat" name="alamat" rows="3"
                  placeholder="Jalan, RT/RW, Desa/Kelurahan, Kota"
                  required>{{ old('alamat') }}</textarea>
        @error('alamat')
          <span class="err-msg show">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label for="produk_id">Pilih Produk</label>
        <select id="produk_id" name="produk_id" required>
          <option value="">-- Pilih produk --</option>
          @forelse($produk as $p)
            <option value="{{ $p->id }}"
                    data-harga="{{ $p->harga }}"
                    data-stok="{{ $p->stok }}"
                    {{ old('produk_id', $produkDipilih) == $p->id ? 'selected' : '' }}>
              {{ $p->nama }} — Rp {{ number_format($p->harga, 0, ',', '.') }}/kg
              (Stok: {{ $p->stok }} kg)
            </option>
          @empty
            <option disabled>Belum ada produk tersedia</option>
          @endforelse
        </select>
        @error('produk_id')
          <span class="err-msg show">{{ $message }}</span>
        @enderror
      </div>

      <div id="infoStok" class="info-stok" style="display:none"></div>

      <div class="form-group">
        <label for="jumlah">Jumlah (kg)</label>
        <input type="number" id="jumlah" name="jumlah"
               value="{{ old('jumlah') }}"
               placeholder="Contoh: 10" min="1" required>
        @error('jumlah')
          <span class="err-msg show">{{ $message }}</span>
        @enderror
      </div>

      <div id="hargaSummary" class="harga-summary" style="display:none">
        <span>Total Harga:</span>
        <strong id="totalHarga">Rp 0</strong>
      </div>

      <button type="submit"
              style="width:100%;padding:14px;background:#2f855a;color:white;border:none;
                     border-radius:40px;font-size:1.05rem;font-weight:700;cursor:pointer;
                     margin-top:8px;transition:all .2s">
        🛒 Buat Pre-Order
      </button>
    </form>
  </section>

  <div class="button-center">
    <a href="{{ route('beranda') }}" class="btn-kembali">← Kembali ke Beranda</a>
  </div>

@endsection

@push('scripts')
<script>
  const selectProduk = document.getElementById('produk_id');
  const inputJumlah  = document.getElementById('jumlah');
  const infoStok     = document.getElementById('infoStok');
  const hargaSummary = document.getElementById('hargaSummary');
  const totalHarga   = document.getElementById('totalHarga');

  const updateUI = () => {
    const opt    = selectProduk?.options[selectProduk.selectedIndex];
    const harga  = parseInt(opt?.dataset.harga || 0);
    const stok   = parseInt(opt?.dataset.stok  || 0);
    const jumlah = parseInt(inputJumlah?.value  || 0);

    if (opt?.value) {
      const menipis = stok > 0 && stok <= 20;
      infoStok.textContent  = `Stok tersedia: ${stok} kg${menipis ? ' — ⚠ Hampir habis!' : ''}`;
      infoStok.className    = `info-stok ${menipis ? 'warn' : 'ok'}`;
      infoStok.style.display = 'block';
    } else {
      infoStok.style.display = 'none';
    }

    if (opt?.value && jumlah > 0 && harga > 0) {
      totalHarga.textContent  = 'Rp ' + (harga * jumlah).toLocaleString('id-ID');
      hargaSummary.style.display = 'flex';
    } else {
      hargaSummary.style.display = 'none';
    }
  };

  selectProduk?.addEventListener('change', updateUI);
  inputJumlah?.addEventListener('input',  updateUI);
  updateUI(); /

document.addEventListener('DOMContentLoaded', bacaDraft);

async function bacaDraft() {
    const res  = await fetch('{{ route("preorder.cookie.baca") }}');
    const json = await res.json();
    if (json.status === 'ok') {
        document.getElementById('nama').value      = json.data.nama;
        document.getElementById('telepon').value   = json.data.telepon;
        document.getElementById('alamat').value    = json.data.alamat;
        document.getElementById('produk_id').value = json.data.produk_id;
        document.getElementById('jumlah').value    = json.data.jumlah;
    }
}

async function simpanDraft() {
    await fetch('{{ route("preorder.cookie.simpan") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            nama:      document.getElementById('nama').value,
            telepon:   document.getElementById('telepon').value,
            alamat:    document.getElementById('alamat').value,
            produk_id: document.getElementById('produk_id').value,
            jumlah:    document.getElementById('jumlah').value,
        }),
    });
}
</script>
@endpush