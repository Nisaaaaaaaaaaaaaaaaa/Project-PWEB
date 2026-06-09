const KEY_ORDERS       = 'panenku_orders';
const KEY_STOK         = 'panenku_stok';
const KEY_PRODUK_CUSTOM = 'panenku_produk_custom';

const PRODUK_DEFAULT = [
  { id: 'padi',   nama: 'Padi',   harga: 6000,  stokAwal: 500,  img: 'gambbar/padi.jpg'   },
  { id: 'jagung', nama: 'Jagung', harga: 5000,  stokAwal: 300,  img: 'gambar/jagung.jpg' },
  { id: 'tomat',  nama: 'Tomat',  harga: 8000,  stokAwal: 200,  img: 'gambar/tomat.jpg'  },
  { id: 'cabai',  nama: 'Cabai',  harga: 35000, stokAwal: 100,  img: 'gambar/cabai.webp' },
  { id: 'timun',  nama: 'Timun',  harga: 7000,  stokAwal: 150,  img: 'gambar/timun.jpeg' },
];

const getProdukCustom = () =>
  JSON.parse(localStorage.getItem(KEY_PRODUK_CUSTOM) || '[]');

const saveProdukCustom = (list) =>
  localStorage.setItem(KEY_PRODUK_CUSTOM, JSON.stringify(list));

const getProdukData = () => {
  const custom = getProdukCustom();
  const overrides = {};
  custom.forEach(p => { overrides[p.id] = p; });
  const defaults = PRODUK_DEFAULT.map(p => overrides[p.id] ? overrides[p.id] : p);
  const customOnly = custom.filter(p => !PRODUK_DEFAULT.find(d => d.id === p.id));
  return [...defaults, ...customOnly];
};

Object.defineProperty(window, 'PRODUK_DATA', { get: getProdukData });
const getStok = () => {
  const tersimpan = localStorage.getItem(KEY_STOK);
  if (tersimpan) return JSON.parse(tersimpan);
  const stokAwal = PRODUK_DATA.reduce((acc, p) => {
    acc[p.id] = p.stokAwal;
    return acc;
  }, {});
  localStorage.setItem(KEY_STOK, JSON.stringify(stokAwal));
  return stokAwal;
};

const saveStok = (stok) =>
  localStorage.setItem(KEY_STOK, JSON.stringify(stok));
const getStokProduk = (id) => getStok()[id] ?? 0;
const cekStokCukup = (id, jumlah) => getStokProduk(id) >= jumlah;
const kurangiStok = (id, jumlah) => {
  const stok = getStok();
  stok[id] = Math.max(0, (stok[id] || 0) - jumlah);
  saveStok(stok);
};

const getOrders = () =>
  JSON.parse(localStorage.getItem(KEY_ORDERS) || '[]');
const saveOrders = (orders) =>
  localStorage.setItem(KEY_ORDERS, JSON.stringify(orders));
const tambahOrder = (orderBaru) => {
  const orders = getOrders();
  orders.push(orderBaru);      
  saveOrders(orders);
};

const ubahStatusOrder = (id, statusBaru) => {
  const orders = getOrders().map(o =>  
    o.id === id ? { ...o, status: statusBaru } : o
  );
  saveOrders(orders);
};

const hapusOrderById = (id) => {
  const orders = getOrders().filter(o => o.id !== id);  
  saveOrders(orders);
};

const hapusSemuaOrder = () => localStorage.removeItem(KEY_ORDERS);
const formatRupiah = (angka) => 'Rp ' + angka.toLocaleString('id-ID');
const getTanggalHariIni = () =>
  new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
const el  = (id)  => document.getElementById(id);
const qsa = (sel) => document.querySelectorAll(sel);

const buatModalAlert = () => {
  if (el('modalAlert')) return;
  const div = document.createElement('div');
  div.id = 'modalAlert';
  div.setAttribute('role', 'dialog');
  div.setAttribute('aria-modal', 'true');
  div.setAttribute('aria-labelledby', 'modalJudul');
  div.innerHTML = `
    <div class="modal-backdrop"></div>
    <div class="modal-box" role="document">
      <div class="modal-ikon" id="modalIkon" aria-hidden="true"></div>
      <h3 id="modalJudul"></h3>
      <p  id="modalPesan"></p>
      <div class="modal-actions">
        <button class="modal-btn" id="modalOk">OK</button>
      </div>
    </div>`;
  document.body.appendChild(div);
  el('modalOk').addEventListener('click', tutupModal);
  div.querySelector('.modal-backdrop').addEventListener('click', tutupModal);
};

const showAlert = (judul, pesan, tipe = 'success', callback = null) => {
  buatModalAlert();
  const ikonMap = { success: '✅', error: '❌', warning: '⚠️' };
  el('modalIkon').textContent  = ikonMap[tipe] || 'ℹ️';
  el('modalJudul').textContent = judul;
  el('modalPesan').textContent = pesan;
  el('modalAlert').className   = `modal-alert modal-${tipe} show`;
  el('modalOk')._cb = callback;
};

const tutupModal = () => {
  const modal = el('modalAlert');
  if (!modal) return;
  modal.classList.remove('show');
  const cb = el('modalOk')._cb;
  if (cb) { el('modalOk')._cb = null; cb(); }
};

const showToast = (pesan, tipe = 'success') => {
  const toast = el('toast');
  if (!toast) return;
  toast.textContent = pesan;
  toast.className   = `toast toast-${tipe} show`;
  setTimeout(() => toast.classList.remove('show'), 3500);
};

const showErr = (errId, inputId) => {
  el(errId)?.classList.add('show');
  el(inputId)?.classList.add('input-error');
};

const resetErrors = () => {
  qsa('.err-msg').forEach(e => e.classList.remove('show'));
  qsa('input, select, textarea').forEach(e => e.classList.remove('input-error'));
};

const initBeranda = () => {
  const statsRow = el('statsRow');
  if (!statsRow) return;

  const orders   = getOrders();
  const menunggu = orders.filter(o => o.status === 'Menunggu').length;
  const diproses = orders.filter(o => o.status === 'Diproses').length;
  const selesai  = orders.filter(o => o.status === 'Selesai').length;

  statsRow.innerHTML = `
    <div class="stat-card">
      <div class="stat-num">${orders.length}</div>
      <div class="stat-label">Total Pesanan</div>
    </div>
    <div class="stat-card">
      <div class="stat-num" style="color:#d97706">${menunggu}</div>
      <div class="stat-label">Menunggu</div>
    </div>
    <div class="stat-card">
      <div class="stat-num" style="color:#2f855a">${diproses}</div>
      <div class="stat-label">Diproses</div>
    </div>
    <div class="stat-card">
      <div class="stat-num" style="color:#3b82f6">${selesai}</div>
      <div class="stat-label">Selesai</div>
    </div>`;
};

const tampilPreviewGambar = (src) => {
  const preview = el('mpImgPreview');
  const wrap    = el('mpImgWrap');
  const label   = el('mpImgLabel');
  if (!preview || !wrap) return;
  preview.src           = src;
  wrap.style.display    = 'flex';
  if (label) label.style.display = 'none';
};

const resetPreviewGambar = () => {
  const preview = el('mpImgPreview');
  const wrap    = el('mpImgWrap');
  const label   = el('mpImgLabel');
  const input   = el('mpImgFile');
  if (preview) preview.src       = '';
  if (wrap)    wrap.style.display = 'none';
  if (label)   label.style.display = 'flex';
  if (input)   input.value        = '';
};

const initUploadGambar = () => {
  const inputFile = el('mpImgFile');
  if (!inputFile) return;
  el('mpImgUploadArea')?.addEventListener('click', () => inputFile.click());
  const area = el('mpImgUploadArea');
  area?.addEventListener('dragover', (e) => {
    e.preventDefault();
    area.classList.add('drag-over');
  });
  area?.addEventListener('dragleave', () => area.classList.remove('drag-over'));
  area?.addEventListener('drop', (e) => {
    e.preventDefault();
    area.classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (file) bacaFileGambar(file);
  });

  inputFile.addEventListener('change', () => {
    const file = inputFile.files[0];
    if (file) bacaFileGambar(file);
  });

  el('mpImgHapus')?.addEventListener('click', (e) => {
    e.stopPropagation();
    resetPreviewGambar();
    const modal = el('modalProduk');
    if (modal) modal.dataset.imgLama = '';
  });
};

const bacaFileGambar = (file) => {
  if (!file.type.startsWith('image/')) {
    showAlert('File Tidak Valid', 'Harap pilih file gambar (JPG, PNG, WebP, dll).', 'error');
    return;
  }
  if (file.size > 2 * 1024 * 1024) {
    showAlert('File Terlalu Besar', 'Ukuran gambar maksimal 2MB. Kompres dulu sebelum upload.', 'warning');
    return;
  }
  const reader = new FileReader();
  reader.onload = (e) => tampilPreviewGambar(e.target.result);
  reader.readAsDataURL(file);  
};

const bukaModalProduk = (editId = null) => {
  const modal = el('modalProduk');
  if (!modal) return;
  const judulEl = el('modalProdukJudul');

  resetPreviewGambar();

  if (editId) {
    const p = getProdukData().find(x => x.id === editId);
    if (!p) return;
    el('mpNama').value   = p.nama;
    el('mpHarga').value  = p.harga;
    el('mpStok').value   = getStokProduk(editId);
    if (p.img) tampilPreviewGambar(p.img);
    judulEl.textContent  = '✏️ Edit Produk';
    modal.dataset.editId  = editId;
    modal.dataset.imgLama = p.img || '';
  } else {
    el('mpNama').value  = '';
    el('mpHarga').value = '';
    el('mpStok').value  = '';
    judulEl.textContent = '➕ Tambah Produk';
    delete modal.dataset.editId;
    delete modal.dataset.imgLama;
  }
  if (typeof modal.showModal === 'function') {
    modal.showModal();
  } else {
    modal.setAttribute('open', '');
  }
};

const tutupModalProduk = () => {
  const modal = el('modalProduk');
  if (!modal) return;
  if (typeof modal.close === 'function') {
    modal.close();
  } else {
    modal.removeAttribute('open');
  }
};

const initProduk = () => {
  if (!el('bodyProduk')) return;
  renderTabelProduk();
  initUploadGambar();
  el('btnTambahProduk')?.addEventListener('click', () => bukaModalProduk());
  el('btnBatalProduk')?.addEventListener('click', tutupModalProduk);
  el('modalProdukBackdrop')?.addEventListener('click', tutupModalProduk);
  el('btnSimpanProduk')?.addEventListener('click', () => {
    const nama     = el('mpNama')?.value.trim();
    const harga    = parseInt(el('mpHarga')?.value);
    const stokBaru = parseInt(el('mpStok')?.value);
    const modal    = el('modalProduk');
    const editId   = modal?.dataset.editId;
    const imgBaru  = el('mpImgPreview')?.src || '';
    const imgLama  = modal?.dataset.imgLama || '';
    const img      = (imgBaru && imgBaru !== window.location.href) ? imgBaru : imgLama;

    if (!nama || !harga || harga < 1 || isNaN(stokBaru) || stokBaru < 0) {
      showAlert('Form Belum Lengkap', 'Nama, harga, dan stok wajib diisi dengan benar.', 'error');
      return;
    }

    const custom = getProdukCustom();

    if (editId) {
      const existing = custom.findIndex(x => x.id === editId);
      const defaultP = PRODUK_DEFAULT.find(x => x.id === editId);
      const updatedP = {
        id: editId,
        nama,
        harga,
        stokAwal: stokBaru,
        img: img || (defaultP?.img || ''),
      };
      if (existing >= 0) custom[existing] = updatedP;
      else custom.push(updatedP);
      const stok = getStok();
      stok[editId] = stokBaru;
      saveStok(stok);
    } else {
      const newId = 'custom_' + Date.now();
      custom.push({ id: newId, nama, harga, stokAwal: stokBaru, img });
      const stok = getStok();
      stok[newId] = stokBaru;
      saveStok(stok);
    }

    saveProdukCustom(custom);
    tutupModalProduk();
    renderTabelProduk();
    showToast(editId ? '✅ Produk diperbarui!' : '✅ Produk ditambahkan!', 'success');
  });
};

const renderTabelProduk = () => {
  const tbody = el('bodyProduk');
  if (!tbody) return;

  const stok = getStok();
  const produkData = getProdukData();

  tbody.innerHTML = produkData.map(p => {
    const stokSaat = stok[p.id] ?? p.stokAwal;
    const habis    = stokSaat <= 0;
    const menipis  = !habis && stokSaat <= 20;
    const badgeClass = habis ? 'habis' : menipis ? 'warn' : 'ok';
    const badgeLabel = habis ? 'Habis' : `${stokSaat} kg`;
    const isCustom = !PRODUK_DEFAULT.find(d => d.id === p.id);

    return `
      <tr>
        <td><strong>${p.nama}</strong></td>
        <td><img src="${p.img}" alt="Foto ${p.nama}" loading="lazy"></td>
        <td><span class="stok-badge ${badgeClass}" id="stok-${p.id}">${badgeLabel}</span></td>
        <td>${formatRupiah(p.harga)}/kg</td>
        <td style="display:flex;gap:6px;justify-content:center;align-items:center;flex-wrap:wrap">
          ${habis
            ? `<span class="btn-pesan disabled" aria-disabled="true">Stok Habis</span>`
            : `<a href="preorder.html?produk=${p.id}" class="btn-pesan">Pesan</a>`}
          <button class="btn-edit-produk" data-id="${p.id}">✏️ Edit</button>
          ${isCustom ? `<button class="btn-hapus btn-hapus-produk" data-id="${p.id}">🗑</button>` : ''}
        </td>
      </tr>`;
  }).join('');

  tbody.querySelectorAll('.btn-edit-produk').forEach(btn => {
    btn.addEventListener('click', () => bukaModalProduk(btn.dataset.id));
  });

  tbody.querySelectorAll('.btn-hapus-produk').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      const p  = getProdukData().find(x => x.id === id);
      showAlert('Hapus Produk?', `Produk "${p?.nama}" akan dihapus permanen.`, 'warning', () => {
        const custom = getProdukCustom().filter(x => x.id !== id);
        saveProdukCustom(custom);
        renderTabelProduk();
        showToast('🗑 Produk dihapus.', 'warn');
      });
    });
  });
};

const initPreorder = () => {
  const form = el('formPreorder');
  if (!form) return;

  const selectProduk = el('produk');
  if (selectProduk) {
    const stok = getStok();
    const produkData = getProdukData();
    selectProduk.innerHTML =
      '<option value="">-- Pilih produk --</option>' +
      produkData.map(p => {                           
        const s     = stok[p.id] ?? p.stokAwal;
        const habis = s <= 0;
        return `<option value="${p.id}" ${habis ? 'disabled' : ''}>
          ${p.nama} — ${formatRupiah(p.harga)}/kg${habis ? ' (Habis)' : ` — Stok: ${s} kg`}
        </option>`;
      }).join('');
  }

  const updateInfoStok = () => {
    const id       = el('produk')?.value;
    const infoEl   = el('infoStok');
    if (!infoEl) return;
    if (!id) { infoEl.style.display = 'none'; return; }
    const s       = getStokProduk(id);
    const menipis = s > 0 && s <= 20;
    infoEl.textContent  = `Stok tersedia: ${s} kg${menipis ? ' — ⚠ Hampir habis!' : ''}`;
    infoEl.className    = `info-stok ${menipis ? 'warn' : 'ok'}`;
    infoEl.style.display = 'block';
  };

  const updateHarga = () => {
    const id     = el('produk')?.value;
    const jumlah = parseInt(el('jumlah')?.value) || 0;
    const produk = PRODUK_DATA.find(p => p.id === id); 
    const sum    = el('hargaSummary');
    const totEl  = el('totalHarga');
    if (produk && jumlah > 0) {
      if (totEl) totEl.textContent = formatRupiah(produk.harga * jumlah);
      if (sum)   sum.style.display = 'flex';
    } else {
      if (sum) sum.style.display = 'none';
    }
  };

  const produkParam = new URLSearchParams(window.location.search).get('produk');
  if (produkParam && selectProduk) {
    selectProduk.value = produkParam;
    updateInfoStok();
    updateHarga();
  }

  el('produk')?.addEventListener('change', () => { updateInfoStok(); updateHarga(); });
  el('jumlah')?.addEventListener('input', updateHarga);

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    resetErrors();

    const nama     = el('nama')?.value.trim();
    const produkId = el('produk')?.value;
    const jumlah   = parseInt(el('jumlah')?.value);
    const alamat   = el('alamat')?.value.trim();
    const telepon  = el('telepon')?.value.trim();

    const checks = [
      { cond: !nama,                                  err: 'errNama',    input: 'nama'    },
      { cond: !produkId,                              err: 'errProduk',  input: 'produk'  },
      { cond: !jumlah || jumlah < 1,                  err: 'errJumlah',  input: 'jumlah'  },
      { cond: !alamat,                                err: 'errAlamat',  input: 'alamat'  },
      { cond: telepon.replace(/\D/g,'').length < 10,  err: 'errTelepon', input: 'telepon' },
    ];

    let valid = true;
    checks.forEach(({ cond, err, input }) => {
      if (cond) { showErr(err, input); valid = false; }
    });

    if (!valid) {
      showAlert('Form Belum Lengkap', 'Mohon lengkapi semua field yang wajib diisi.', 'error');
      return;
    }

    if (!cekStokCukup(produkId, jumlah)) {
      const sisa    = getStokProduk(produkId);
      const namaProd = PRODUK_DATA.find(p => p.id === produkId)?.nama;  
      showAlert(
        'Stok Tidak Mencukupi ⚠️',
        `Stok ${namaProd} saat ini hanya ${sisa} kg, sedangkan kamu memesan ${jumlah} kg. Silakan kurangi jumlah pesanan.`,
        'warning'
      );
      showErr('errJumlah', 'jumlah');
      return;
    }

    const produk = PRODUK_DATA.find(p => p.id === produkId); 

    const orderBaru = {
      id:      Date.now(),
      nama,
      produkId,
      produk:  produk.nama,
      jumlah,
      alamat,
      telepon,
      total:   produk.harga * jumlah,
      status:  'Menunggu',
      tanggal: getTanggalHariIni(),
    };

    tambahOrder(orderBaru);      
    kurangiStok(produkId, jumlah); 

    form.reset();
    if (el('hargaSummary')) el('hargaSummary').style.display = 'none';
    if (el('infoStok'))     el('infoStok').style.display     = 'none';

    showAlert(
      'Pesanan Berhasil! 🎉',
      `Terima kasih, ${nama}! Pesanan ${jumlah} kg ${produk.nama} senilai ${formatRupiah(produk.harga * jumlah)} berhasil dibuat. Kamu akan diarahkan ke riwayat.`,
      'success',
      () => { window.location.href = 'riwayat.html'; }
    );
  });
};

let currentFilter = 'semua';

const renderRiwayat = (filter = 'semua') => {
  const tbody     = el('bodyRiwayat');
  const emptyEl   = el('emptyState');
  const tabelEl   = el('tabelRiwayat');
  const filterBar = el('filterBar');
  const hapusWrap = el('btnHapusWrap');
  if (!tbody) return;

  const all = getOrders();

  const filtered = filter === 'semua'
    ? all
    : all.filter(o => o.status === filter);

  const adaData = all.length > 0;
  tabelEl  && (tabelEl.style.display  = adaData ? 'table' : 'none');
  filterBar && (filterBar.style.display = adaData ? 'flex'  : 'none');
  hapusWrap && (hapusWrap.style.display = adaData ? 'block' : 'none');
  emptyEl   && (emptyEl.style.display  = adaData ? 'none'  : 'block');
  if (!adaData) return;

  if (filtered.length === 0) {
    tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;color:#888;padding:24px">
      Tidak ada pesanan dengan status ini.</td></tr>`;
    return;
  }

  tbody.innerHTML = filtered.map(o => {
    const sc = { Menunggu:'status-menunggu', Diproses:'status-diproses', Selesai:'status-selesai' }[o.status] || '';
    return `
      <tr data-id="${o.id}">
        <td>${o.tanggal || '-'}</td>
        <td><strong>${o.nama}</strong></td>
        <td>${o.produk}</td>
        <td>${o.jumlah} kg</td>
        <td>${o.total ? formatRupiah(o.total) : '-'}</td>
        <td>
          <select class="status-select ${sc}" data-action="ubah-status" data-id="${o.id}"
            aria-label="Status pesanan ${o.nama}">
            <option value="Menunggu" ${o.status==='Menunggu'?'selected':''}>⏳ Menunggu</option>
            <option value="Diproses" ${o.status==='Diproses'?'selected':''}>🔄 Diproses</option>
            <option value="Selesai"  ${o.status==='Selesai' ?'selected':''}>✅ Selesai</option>
          </select>
        </td>
        <td>
          <button class="btn-hapus" data-action="hapus" data-id="${o.id}">Hapus</button>
        </td>
      </tr>`;
  }).join('');
};

const initRiwayat = () => {
  const tbody = el('bodyRiwayat');
  if (!tbody) return;

  renderRiwayat('semua');

  tbody.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-action="hapus"]');
    if (!btn) return;
    const id    = Number(btn.dataset.id);
    const order = getOrders().find(o => o.id === id);
    showAlert(
      'Hapus Pesanan?',
      `Pesanan ${order?.produk} milik ${order?.nama} akan dihapus permanen.`,
      'warning',
      () => { hapusOrderById(id); renderRiwayat(currentFilter); showToast('🗑 Pesanan dihapus.', 'warn'); }
    );
  });

  tbody.addEventListener('change', (e) => {
    const sel = e.target.closest('[data-action="ubah-status"]');
    if (!sel) return;
    const id         = Number(sel.dataset.id);
    const statusBaru = sel.value;
    ubahStatusOrder(id, statusBaru);
    sel.className = `status-select status-${statusBaru.toLowerCase()}`;
    renderRiwayat(currentFilter);
    showToast(`✅ Status diperbarui: ${statusBaru}`, 'success');
  });

  el('filterBar')?.addEventListener('click', (e) => {
    const btn = e.target.closest('.filter-btn');
    if (!btn) return;
    currentFilter = btn.dataset.filter;
    qsa('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    renderRiwayat(currentFilter);
  });

  el('btnHapusSemua')?.addEventListener('click', () => {
    showAlert(
      'Hapus Semua Riwayat?',
      'Semua data pesanan akan dihapus permanen.',
      'warning',
      () => { hapusSemuaOrder(); renderRiwayat(currentFilter); showToast('🗑 Semua riwayat dihapus.', 'warn'); }
    );
  });
};

document.addEventListener('DOMContentLoaded', () => {
  initBeranda();
  initProduk();
  initPreorder();
  initRiwayat();
});