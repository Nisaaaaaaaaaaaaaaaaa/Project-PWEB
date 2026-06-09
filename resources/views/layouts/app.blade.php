<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PanenKu - Platform Pertanian</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
    function getCookie(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : null;
    }
    const temaInit = getCookie('tema');
    if (temaInit === 'dark' || (!temaInit && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    }
</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header>
        <div class="header-content animate-once">
            <div class="logo-pulse">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" style="width: 80px;">
            </div>
            <div class="title-area">
                <span class="brand">PanenKu</span>
                <p>Platform Digital Penjualan Produk Pertanian</p>
            </div>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'active' : '' }}">Beranda</a></li>

            @auth
                @if(Auth::user()->role === 'admin')
                    <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('produk') }}" class="{{ request()->routeIs('produk*') ? 'active' : '' }}">Kelola Produk</a></li>
                    <li><a href="{{ route('pesanan.admin') }}" class="{{ request()->routeIs('pesanan*') ? 'active' : '' }}">Kelola Pesanan</a></li>
                    <li><a href="{{ route('petani.index') }}" class="{{ request()->routeIs('petani*') ? 'active' : '' }}">Kelola Petani</a></li>

                @elseif(Auth::user()->role === 'petani')
                    <li><a href="{{ route('petani.produk.index') }}" class="{{ request()->routeIs('petani.produk*') ? 'active' : '' }}">🌾 Produk Saya</a></li>
                    <li><a href="{{ route('petani.pesanan.index') }}" class="{{ request()->routeIs('petani.pesanan*') ? 'active' : '' }}">📋 Pesanan Saya</a></li>

                @else
                    <li><a href="{{ route('produk.list') }}" class="{{ request()->routeIs('produk.list') ? 'active' : '' }}">Produk</a></li>
                    <li><a href="{{ route('preorder') }}" class="{{ request()->routeIs('preorder') ? 'active' : '' }}">Pre-order</a></li>
                    <li><a href="{{ route('riwayat') }}" class="{{ request()->routeIs('riwayat') ? 'active' : '' }}">Riwayat</a></li>
                @endif
                
                <li>
                    <a href="{{ route('preferensi') }}" class="{{ request()->routeIs('preferensi') ? 'active' : '' }}">
                    ⚙️ Tampilan
                    </a>
                </li>

                <li style="display:flex;align-items:center;gap:12px;margin-left:auto">
                    <span style="color:white;opacity:0.8">👤 {{ auth()->user()->name }}</span>
                    <button id="btnDarkMode" onclick="toggleDark()"
                            style="background:none;border:none;color:white;cursor:pointer;font-size:1.2rem;padding:0">
                        🌙
                    </button>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0">
                        @csrf
                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                           style="color:white">Keluar</a>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}">Masuk</a></li>
                <li><a href="{{ route('register') }}">Daftar</a></li>
            @endauth
        </ul>
    </nav>

    <main class="animate-once">
        @if(session('success'))
            <div style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;padding:12px 20px;border-radius:8px;margin-bottom:20px;">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:12px 20px;border-radius:8px;margin-bottom:20px;">
                ❌ {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <p>&copy; 2026 PanenKu. Dibuat dengan ❤️ untuk Petani Indonesia.</p>
    </footer>

    <script>
        function setCookie(name, value, days) {
            const d = new Date();
            d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = `${name}=${value};expires=${d.toUTCString()};path=/`;
        }
        function getCookie(name) {
            const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            return match ? match[2] : null;
        }
        function deleteCookie(name) {
            setCookie(name, '', -1);
        }
        function toggleDark() {
            const isDark = document.documentElement.classList.toggle('dark');
            setCookie('tema', isDark ? 'dark' : 'light', 30);
            document.getElementById('btnDarkMode').textContent = isDark ? '☀️' : '🌙';
        }
        const temaAwal = getCookie('tema');
        if (temaAwal === 'dark' || (!temaAwal && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            const btn = document.getElementById('btnDarkMode');
            if (btn) btn.textContent = '☀️';
        }
    </script>

    @stack('scripts')
</body>
</html>