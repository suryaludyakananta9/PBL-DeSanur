<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DeSanur - Jelajahi Sanur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="/" aria-label="DeSanur Beranda">
            <span class="brand-mark">
                <svg viewBox="0 0 32 32" aria-hidden="true"><circle cx="16" cy="16" r="13" fill="none" stroke="currentColor" stroke-width="2"/><path d="M7 21c4-5 8-5 12-1 2 2 4 2 7 0" fill="none" stroke="currentColor" stroke-width="2"/><path d="M19 9c-3 2-3 6 0 8" fill="none" stroke="currentColor" stroke-width="2"/></svg>
            </span>
            <span>DeSanur<span class="brand-dot">.</span></span>
        </a>
        <nav class="main-nav" aria-label="Navigasi utama">
            <a class="active" href="#">Beranda</a>
            <a href="#destinasi">Destinasi</a>
            <a href="#rekomendasi">Akomodasi</a>
            <a href="#informasi">Informasi</a>
            <a href="#event">Event</a>
            <a href="#wishlist">Wishlist</a>
            <a href="#kontak">Kontak</a>
        </nav>
        <a class="login-btn" href="#">Login</a>
    </div>
</header>

<main>
    <section class="hero">
        <img src="/images/hero.jpg" alt="Pemandangan Pantai Sanur">
        <div class="hero-overlay"></div>
        <div class="hero-inner">
            <div class="hero-content">
                <div class="eyebrow dark"><span></span> SRIWASI EKPLOERASI PESAN WONDI SAHAR</div>
                <h1>Temukan Cara Terbaik<br>Menikmati <span>DeSanur.</span></h1>
                <p>Jelajahi destinasi terbaik dan pengalaman lokal yang<br>autentik, Cerita budaya dimulai di sini.</p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="#rekomendasi">Jelajahi Destinasi <b>→</b></a>
                    <a class="btn btn-light" href="#inspirasi">Lihat Rekomendasi</a>
                </div>
                <div class="hero-meta"><span>Destinasi Terfavorit</span><i></i><span>Lebih dekat, lebih berkesan</span></div>
            </div>
            <div class="weather-card">
                <div class="weather-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2M5 5l1.5 1.5M17.5 17.5 19 19M19 5l-1.5 1.5M6.5 17.5 5 19" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></div>
                <div><small>SKRANKU 24°C</small><strong>Cerah & Bersahabat</strong></div>
            </div>
        </div>
    </section>

    <section class="category-wrap" id="destinasi">
        <div class="category-box">
            <div class="category-heading"><span>PLIH SUKANAWAI</span><h2>Sanur punya<br>banyak cerita.</h2></div>
            <div class="categories">
                <a class="category" href="#"><span class="category-icon"><svg viewBox="0 0 24 24"><path d="M4 18h16M6 18v-7h12v7M8 11l4-5 4 5M6 14h12M12 6v12" fill="none" stroke="currentColor" stroke-width="1.6"/></svg></span><span><strong>Pantal</strong><small>Pasir putih & pualam ombak</small></span></a>
                <a class="category" href="#"><span class="category-icon"><svg viewBox="0 0 24 24"><path d="M4 8h16v12H4zM7 8V5h10v3M9 12h6M12 12v8M7 20h10" fill="none" stroke="currentColor" stroke-width="1.6"/></svg></span><span><strong>Budaya</strong><small>Jejak warisan tradisi</small></span></a>
                <a class="category" href="#"><span class="category-icon"><svg viewBox="0 0 24 24"><path d="M7 20V9h10v11M5 20h14M9 9V5h6v4M12 5v15M9 13h6" fill="none" stroke="currentColor" stroke-width="1.6"/></svg></span><span><strong>Kuliner</strong><small>Bisa bicik, rasa, Sukurda</small></span></a>
                <a class="category" href="#"><span class="category-icon"><svg viewBox="0 0 24 24"><circle cx="7" cy="16" r="3" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="18" cy="7" r="3" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M9.5 14.5 16 9M3 20c2.5-1.5 5.5-1.5 8 0M14 3c4 2 5 5 4 8" fill="none" stroke="currentColor" stroke-width="1.6"/></svg></span><span><strong>Aktivitas</strong><small>Petualangan berkesan</small></span></a>
            </div>
        </div>
    </section>

    <section class="recommendations" id="rekomendasi">
        <div class="section-head">
            <div><span>PILIHAN UNTUK CERITA BARU</span><h2>Rekomendasi untuk Kamu</h2><p>Dari suasana yang tenang, sampai momen seru yang ingin kamu ulangi.</p></div>
            <a href="#">Lihat Semua Destinasi <b>→</b></a>
        </div>
        <div class="cards">
            <article class="place-card">
                <div class="card-image"><img src="/images/pantai-sanur.jpg" alt="Pantai Sanur"><span class="badge">Pantal</span><button class="heart" aria-label="Favorit">♡</button></div>
                <div class="card-body"><small>⌖&nbsp; Sanur Kauh</small><h3>Pantai Sanur</h3><p>Nikmati matahari terbit yang tenang, pasir putih yang hangat, dan suasana otentik.</p><div class="rating">Gratis</div><div class="card-foot"><span>Lihat detail ↗</span><a href="#">Simpan ♡</a></div></div>
            </article>
            <article class="place-card">
                <div class="card-image"><img src="/images/museum.jpg" alt="Museum Le Mayeur"><span class="badge">Budaya</span><button class="heart" aria-label="Favorit">♡</button></div>
                <div class="card-body"><small>⌖&nbsp; Sanur Kauh</small><h3>Museum Le Mayeur</h3><p>Menghirup kisah seni dan budaya Bali dalam Museum Le Mayeur.</p><div class="rating">Rp25.000</div><div class="card-foot"><span>Lihat detail ↗</span><a href="#">Simpan ♡</a></div></div>
            </article>
            <article class="place-card">
                <div class="card-image"><img src="/images/karang.jpg" alt="Pantai Karang"><span class="badge">Saran</span><button class="heart" aria-label="Favorit">♡</button></div>
                <div class="card-body"><small>⌖&nbsp; Sanur Kayu</small><h3>Pantai Karang</h3><p>Tentukan kesenangan di sini pantai dengan pemandangan laut yang memukau.</p><div class="rating">Gratis</div><div class="card-foot"><span>Lihat detail ↗</span><a href="#">Simpan ♡</a></div></div>
            </article>
            <article class="place-card">
                <div class="card-image"><img src="/images/bersih.jpg" alt="Bersepeda di Sanur"><span class="badge">Aktivitas</span><button class="heart" aria-label="Favorit">♡</button></div>
                <div class="card-body"><small>⌖&nbsp; Sanur Kauh</small><h3>Bersepeda di Sanur</h3><p>Kayuh santai di sepanjang pantai, dan temukan sudut Indah Sanur.</p><div class="rating">Rp50.000</div><div class="card-foot"><span>Lihat detail ↗</span><a href="#">Simpan ♡</a></div></div>
            </article>
        </div>
        <p class="recommend-note">Ingat pertidikan mau dapat Anda lebih mengenal Sanur.</p>
    </section>

    <section class="explore-section" id="inspirasi">
        <div class="explore-image"><img src="/images/feature.jpg" alt="Pantai Sanur"></div>
        <div class="explore-copy">
            <span>SANUR, LEBIH PERSONAL</span>
            <h2>Jelajahi Sanur.<br>Dengan caramu sendiri.</h2>
            <p>Tak perlu terburu-buru. Temukan pengalamammu, pilih yang kamu suka, dan berikan perjalanan menjadi dengan sendiri nya.</p>
            <ul><li><b>✓</b> Temukan destinasi</li><li><b>✓</b> Kenali pengalaman lokal</li><li><b>✓</b> Simpan pilihan di Wishlist</li></ul>
            <a class="btn btn-primary" href="#">Jelajahi Pilihan Sanur <span>→</span></a>
        </div>
    </section>

    <section class="story-banner" id="event">
        <div class="story-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2M5 5l1.5 1.5M17.5 17.5 19 19M19 5l-1.5 1.5M6.5 17.5 5 19" fill="none" stroke="currentColor" stroke-width="1.6"/></svg></div>
        <div><h3>Satu hari, banyak cerita.</h3><p>Mulai jelajah pengalamanmu yang berkesan di Sanur.</p></div>
        <a href="#">Jelajahi sekarang <b>→</b></a>
    </section>
</main>

<footer id="kontak">
    <div class="footer-grid">
        <div class="footer-about"><a class="brand" href="#"><span class="brand-mark"><svg viewBox="0 0 32 32"><circle cx="16" cy="16" r="13" fill="none" stroke="currentColor" stroke-width="2"/><path d="M7 21c4-5 8-5 12-1 2 2 4 2 7 0" fill="none" stroke="currentColor" stroke-width="2"/></svg></span><span>DeSanur<span class="brand-dot">.</span></span></a><p>Temukan pengalaman Sanur yang lebih dekat, hemat, dan berkesan.<br>Wesenda Paket.</p></div>
        <div><h4>Jelajahi</h4><a href="#">Beranda</a><a href="#">Destinasi</a><a href="#">Akomodasi</a><a href="#">Event</a></div>
        <div><h4>Bantuan</h4><a href="#">Kontak</a><a href="#">Wishlist</a><a href="#">Login / Registrasi</a></div>
        <div><h4>Tentang DeSanur</h4><p>Platform wisata lokal untuk membantu wisatawan menemukan Sanur.<br><span>●  Desa Adat Sanur, Bali</span></p></div>
    </div>
    <div class="footer-bottom"><span>© 2026 DeSanur. Didsatu untuk pengalaman yang lebih bermakna.</span><span>Draftsandi - dengan Pintu untuk sanuwu Yura.</span></div>
</footer>
</body>
</html>