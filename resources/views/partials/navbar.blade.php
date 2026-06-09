<nav aria-label="Navigasi utama">
  <ul>
    <li>
      <a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'active' : '' }}">
        🏠 Beranda
      </a>
    </li>

    {{-- Semua user yang login bisa lihat produk --}}
    @auth
      <li>
        @if(auth()->user()->role === 'admin')
          <a href="{{ route('produk') }}" class="{{ request()->routeIs('produk') ? 'active' : '' }}">
            🌾 Kelola Produk
          </a>
        @else
          <a href="{{ route('produk.list') }}" class="{{ request()->routeIs('produk.list') ? 'active' : '' }}">
            🌾 Produk
          </a>
        @endif
      </li>
    @endauth

    {{-- Pre-order & Riwayat: hanya pelanggan --}}
    @auth
      @if(auth()->user()->role === 'pelanggan')
        <li>
          <a href="{{ route('preorder') }}" class="{{ request()->routeIs('preorder*') ? 'active' : '' }}">
            📦 Pre-Order
          </a>
        </li>
        <li>
          <a href="{{ route('riwayat') }}" class="{{ request()->routeIs('riwayat*') ? 'active' : '' }}">
            📋 Riwayat
          </a>
        </li>
      @endif
    @endauth

    {{-- Dashboard: hanya admin --}}
    @auth
      @if(auth()->user()->role === 'admin')
        <li>
          <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            📊 Dashboard
          </a>
        </li>
        <li>
          <a href="{{ route('pesanan.admin') }}" class="{{ request()->routeIs('pesanan.*') ? 'active' : '' }}">
            📋 Pesanan
          </a>
        </li>
      @endif
    @endauth

    <li>
      <a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'active' : '' }}">
        ℹ️ Tentang
      </a>
    </li>

    {{-- Login / Logout --}}
    @guest
      <li><a href="{{ route('login') }}">🔐 Login</a></li>
    @else
      <li>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
          @csrf
          <button type="submit" style="background:none;border:none;cursor:pointer;color:inherit;font:inherit;padding:0">
            🚪 Logout ({{ auth()->user()->name }})
          </button>
        </form>
      </li>
    @endguest
  </ul>
</nav>