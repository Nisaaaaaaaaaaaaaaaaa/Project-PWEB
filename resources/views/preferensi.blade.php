@extends('layouts.app')

@section('title', 'Preferensi Tampilan')
@section('description', 'Atur tema dan ukuran font sesuai kenyamanan kamu.')

@section('content')

  <section aria-labelledby="judulPref" class="card animate-once">
    <h2 id="judulPref">⚙️ Preferensi Tampilan</h2>
    <p style="color:#718096;margin-bottom:24px">
      Pengaturan ini disimpan di browser kamu selama 30 hari.
    </p>

    <div id="notif" role="alert" style="display:none"
         class="flash-msg flash-success">
    </div>

    <div id="formPref">

      <div class="form-group">
        <label>Tema Warna</label>
        <div class="pref-options" role="group" aria-label="Pilih tema">

          <label class="pref-card {{ $pref['tema'] === 'terang' ? 'active' : '' }}"
                 data-value="terang" data-group="tema">
            <input type="radio" name="tema" value="terang"
                   {{ $pref['tema'] === 'terang' ? 'checked' : '' }}
                   style="display:none">
            <span class="pref-ikon">☀️</span>
            <span class="pref-label">Terang</span>
          </label>

          <label class="pref-card {{ $pref['tema'] === 'gelap' ? 'active' : '' }}"
                 data-value="gelap" data-group="tema">
            <input type="radio" name="tema" value="gelap"
                   {{ $pref['tema'] === 'gelap' ? 'checked' : '' }}
                   style="display:none">
            <span class="pref-ikon">🌙</span>
            <span class="pref-label">Gelap</span>
          </label>

          <label class="pref-card {{ $pref['tema'] === 'hijau' ? 'active' : '' }}"
                 data-value="hijau" data-group="tema">
            <input type="radio" name="tema" value="hijau"
                   {{ $pref['tema'] === 'hijau' ? 'checked' : '' }}
                   style="display:none">
            <span class="pref-ikon">🌿</span>
            <span class="pref-label">Hijau</span>
          </label>

        </div>
      </div>

      <div class="form-group" style="margin-top:24px">
        <label>Ukuran Teks</label>
        <div class="pref-options" role="group" aria-label="Pilih ukuran font">

          <label class="pref-card {{ $pref['ukuran_font'] === 'kecil' ? 'active' : '' }}"
                 data-value="kecil" data-group="ukuran_font">
            <input type="radio" name="ukuran_font" value="kecil"
                   {{ $pref['ukuran_font'] === 'kecil' ? 'checked' : '' }}
                   style="display:none">
            <span class="pref-ikon" style="font-size:0.85rem">A</span>
            <span class="pref-label">Kecil</span>
          </label>

          <label class="pref-card {{ $pref['ukuran_font'] === 'sedang' ? 'active' : '' }}"
                 data-value="sedang" data-group="ukuran_font">
            <input type="radio" name="ukuran_font" value="sedang"
                   {{ $pref['ukuran_font'] === 'sedang' ? 'checked' : '' }}
                   style="display:none">
            <span class="pref-ikon" style="font-size:1.1rem">A</span>
            <span class="pref-label">Sedang</span>
          </label>

          <label class="pref-card {{ $pref['ukuran_font'] === 'besar' ? 'active' : '' }}"
                 data-value="besar" data-group="ukuran_font">
            <input type="radio" name="ukuran_font" value="besar"
                   {{ $pref['ukuran_font'] === 'besar' ? 'checked' : '' }}
                   style="display:none">
            <span class="pref-ikon" style="font-size:1.4rem">A</span>
            <span class="pref-label">Besar</span>
          </label>

        </div>
      </div>

      <div style="display:flex;gap:12px;margin-top:32px">
        <button id="btnSimpan" type="button"
                style="flex:1;padding:14px;background:#2f855a;color:white;border:none;
                       border-radius:40px;font-size:1rem;font-weight:700;cursor:pointer;
                       transition:all .2s">
          💾 Simpan Preferensi
        </button>
        <button id="btnReset" type="button"
                style="padding:14px 24px;background:#fee2e2;color:#b91c1c;border:none;
                       border-radius:40px;font-size:1rem;font-weight:600;cursor:pointer;
                       transition:all .2s">
          🔄 Reset
        </button>
      </div>

    </div>
  </section>

  <div class="button-center">
    <a href="{{ route('beranda') }}" class="btn-kembali">← Kembali ke Beranda</a>
  </div>

@endsection

@push('scripts')
<script>
  function setCookie(name, value, days) {
    const d = new Date();
    d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = `${name}=${value};expires=${d.toUTCString()};path=/`;
}
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;

  document.querySelectorAll('.pref-card').forEach(card => {
    card.addEventListener('click', () => {
      const group = card.dataset.group;
      document.querySelectorAll(`.pref-card[data-group="${group}"]`)
              .forEach(c => c.classList.remove('active'));
      card.classList.add('active');
      card.querySelector('input').checked = true;
    });
  });

  const getNilai = (group) =>
    document.querySelector(`.pref-card[data-group="${group}"].active`)
            ?.dataset.value ?? null;

  const tampilNotif = (pesan, tipe = 'success') => {
    const el = document.getElementById('notif');
    el.textContent = pesan;
    el.className   = `flash-msg flash-${tipe}`;
    el.style.display = 'block';
    setTimeout(() => el.style.display = 'none', 3000);
  };

  const terapkan = (data) => {
    if (!data) return;

    const ukuran = { kecil: '14px', sedang: '16px', besar: '18px' };
    document.documentElement.style.fontSize = ukuran[data.ukuran_font] ?? '16px';

    if (data.tema === 'gelap') {
        document.documentElement.classList.add('dark');
        setCookie('tema', 'dark', 30);
    } else {
        document.documentElement.classList.remove('dark');
        setCookie('tema', 'light', 30);
    }

    const btn = document.getElementById('btnDarkMode');
    if (btn) btn.textContent = data.tema === 'gelap' ? '☀️' : '🌙';
};

  document.getElementById('btnSimpan').addEventListener('click', async () => {
    const payload = {
      tema:        getNilai('tema'),
      ukuran_font: getNilai('ukuran_font'),
    };

    if (!payload.tema || !payload.ukuran_font) {
      tampilNotif('❌ Pilih tema dan ukuran font dulu.', 'error');
      return;
    }

    try {
      const res  = await fetch('{{ route("preferensi.simpan") }}', {
        method:  'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF,
        },
        body: JSON.stringify(payload),
      });

      const json = await res.json();

      if (json.status === 'ok') {
        terapkan(json.data);
        tampilNotif('✅ ' + json.message);
      } else {
        tampilNotif('❌ Gagal menyimpan.', 'error');
      }
    } catch (err) {
      tampilNotif('❌ Terjadi kesalahan koneksi.', 'error');
      console.error(err);
    }
  });

  document.getElementById('btnReset').addEventListener('click', async () => {
    try {
      const res  = await fetch('{{ route("preferensi.reset") }}', {
        method:  'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF },
      });

      const json = await res.json();

      if (json.status === 'ok') {
        document.body.classList.remove('tema-terang', 'tema-gelap', 'tema-hijau');
        document.documentElement.style.fontSize = '16px';
        tampilNotif('🔄 ' + json.message);
        setTimeout(() => location.reload(), 1000);
      }
    } catch (err) {
      tampilNotif('❌ Terjadi kesalahan.', 'error');
    }
  });

  (async () => {
    try {
      const res  = await fetch('{{ route("preferensi.baca") }}');
      const json = await res.json();
      if (json.status === 'ok') terapkan(json.data);
    } catch (_) {}
  })();
</script>
@endpush