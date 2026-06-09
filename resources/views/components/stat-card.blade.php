@props([
    'judul' => 'Label',
    'nilai' => '0',
    'ikon'  => '📦',
    'warna' => '#2f855a',
])

<div class="stat-card" role="listitem">
  <div class="stat-ikon" aria-hidden="true" style="font-size:1.8rem;margin-bottom:6px">
    {{ $ikon }}
  </div>
  <div class="stat-num" style="color:{{ $warna }}">
    {{ $nilai }}
  </div>
  <div class="stat-label">{{ $judul }}</div>
</div>