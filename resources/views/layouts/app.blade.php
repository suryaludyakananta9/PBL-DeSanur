<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','DeSanur') - Jelajahi Sanur</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="app-body">
<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="{{ route('home') }}"><span class="brand-mark"><svg viewBox="0 0 32 32"><circle cx="16" cy="16" r="13" fill="none" stroke="currentColor" stroke-width="2"/><path d="M7 21c4-5 8-5 12-1 2 2 4 2 7 0" fill="none" stroke="currentColor" stroke-width="2"/></svg></span><span>DeSanur<span class="brand-dot">.</span></span></a>
        <button class="mobile-menu" type="button" aria-label="Menu">☰</button>
        <nav class="main-nav">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home')?'active':'' }}">Beranda</a>
            <a href="{{ route('explore') }}" class="{{ request()->routeIs('explore')?'active':'' }}">Explore Sanur</a>
            <a href="{{ route('search') }}" class="{{ request()->routeIs('search')?'active':'' }}">Cari & Filter</a>
            <a href="{{ route('recommendation') }}" class="{{ request()->routeIs('recommendation')?'active':'' }}">Rekomendasi</a>
            <a href="{{ route('planner') }}" class="{{ request()->routeIs('planner')?'active':'' }}">Trip Planner</a>
            @auth
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('traveler.profile') }}">{{ auth()->user()->name }}</a>
            @else
                <a href="{{ route('login') }}">Login</a>
            @endauth
        </nav>
        @auth
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="login-btn" type="submit">Logout</button></form>
        @else
            <a class="login-btn" href="{{ route('login') }}">Login</a>
        @endauth
    </div>
</header>
@if(session('success'))<div class="toast">{{ session('success') }}</div>@endif
@yield('content')
<footer>
    <div class="footer-grid"><div class="footer-about"><a class="brand" href="{{ route('home') }}"><span class="brand-mark">◎</span><span>DeSanur<span class="brand-dot">.</span></span></a><p>Temukan pengalaman Sanur yang lebih dekat, autentik, dan bermakna.</p></div><div><h4>Jelajahi</h4><a href="{{ route('explore') }}">Destinasi</a><a href="{{ route('recommendation') }}">Rekomendasi</a></div><div><h4>Bantuan</h4><a href="{{ route('login') }}">Login / Registrasi</a><a href="{{ route('traveler.favorites') }}">Wishlist</a></div><div><h4>Tentang DeSanur</h4><p>Platform wisata lokal kawasan Sanur, Bali.</p></div></div>
    <div class="footer-bottom"><span>© 2026 DeSanur.</span><span>Desa Adat Sanur, Bali</span></div>
</footer>
<script>document.querySelector('.mobile-menu')?.addEventListener('click',()=>document.querySelector('.main-nav')?.classList.toggle('open'));</script>
@stack('scripts')
</body></html>