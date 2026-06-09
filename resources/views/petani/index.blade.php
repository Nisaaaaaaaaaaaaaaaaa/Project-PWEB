@extends('layouts.app')

@section('title', 'Data Petani')

@section('content')
<section class="card animate-once">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <h2>🌾 Data Petani</h2>
        <a href="{{ route('petani.create') }}" class="btn-tambah-produk">+ Tambah Petani</a>
    </div>

    <div style="margin-bottom:16px">
        <input type="text" id="searchInput"
               placeholder="🔍 Cari petani (nama, kode, komoditas)..."
               style="width:100%;padding:10px 16px;border:1px solid #ddd;border-radius:40px;font-size:1rem;box-sizing:border-box">
    </div>

    <div id="searchResult" style="display:none">
        <div class="table-wrapper">
            <table aria-label="Hasil pencarian">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th scope="col">Telepon</th>
                        <th>Komoditas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="searchBody"></tbody>
            </table>
        </div>
    </div>

    {{-- Tabel Utama --}}
    <div id="tabelUtama">
        <div class="table-wrapper">
            <table aria-label="Tabel data petani">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th scope="col">Telepon</th>
                        <th>Komoditas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($petani as $p)
                        <tr>
                            <td>{{ $loop->iteration + ($petani->currentPage() - 1) * $petani->perPage() }}</td>
                            <td>
                                @if($p->foto)
                                    <img src="{{ asset('storage/' . $p->foto) }}"
                                        alt="Foto {{ $p->nama }}"
                                        style="width:45px;height:45px;object-fit:cover;border-radius:50%">
                                @else
                                    <span style="color:#aaa">—</span>
                                @endif
                            </td>
                            <td>{{ $p->kode }}</td>
                            <td><strong>{{ $p->nama }}</strong></td>
                            <td>{{ $p->email }}</td>
                            <td>{{ $p->telepon ?? '-' }}</td>
                            <td><span class="stok-badge ok">{{ $p->komoditas }}</span></td>
                            <td style="display:flex;gap:6px;flex-wrap:wrap;justify-content:center">
                                <a href="{{ route('petani.show', $p->id) }}" class="btn-edit-produk">👁 Detail</a>
                                <a href="{{ route('petani.edit', $p->id) }}" class="btn-edit-produk">✏️ Edit</a>
                                <form method="POST" action="{{ route('petani.destroy', $p->id) }}"
                                    onsubmit="return confirm('Hapus petani {{ $p->nama }}?')"
                                    style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-hapus">🗑</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;color:#888;padding:24px">
                                Belum ada data petani.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px">
            {{ $petani->links() }}
        </div>
    </div>

</section>

<div class="button-center">
    <a href="{{ route('beranda') }}" class="btn-kembali">← Kembali ke Beranda</a>
</div>
@endsection

@push('scripts')
<script>
const searchInput = document.getElementById('searchInput');
const searchResult = document.getElementById('searchResult');
const searchBody = document.getElementById('searchBody');
const tabelUtama = document.getElementById('tabelUtama');

searchInput.addEventListener('input', async function() {
    const q = this.value.trim();

    if (q.length === 0) {
        searchResult.style.display = 'none';
        tabelUtama.style.display = 'block';
        return;
    }

    try {
        const response = await fetch(`/petani/search?q=${encodeURIComponent(q)}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        searchBody.innerHTML = '';

        if (data.length === 0) {
            searchBody.innerHTML = `<tr><td colspan="6" style="text-align:center;color:#888;padding:16px">Tidak ada hasil untuk "${q}"</td></tr>`;
        } else {
            data.forEach((p, i) => {
                searchBody.innerHTML += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${p.kode}</td>
                        <td><strong>${p.nama}</strong></td>
                        <td>${p.email}</td>
                        <td>${p.telepon ?? '-'}</td>
                        <td><span class="stok-badge ok">${p.komoditas}</span></td>
                        <td style="display:flex;gap:6px">
                            <a href="/petani/${p.id}" class="btn-edit-produk">👁 Detail</a>
                            <a href="/petani/${p.id}/edit" class="btn-edit-produk">✏️ Edit</a>
                        </td>
                    </tr>`;
            });
        }

        tabelUtama.style.display = 'none';
        searchResult.style.display = 'block';

    } catch(e) {
        console.error('Search error:', e);
    }
});
</script>
@endpush