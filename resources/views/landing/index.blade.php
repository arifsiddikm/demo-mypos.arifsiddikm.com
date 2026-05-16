<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['cafe_name'] ?? 'MyPOS Cafe' }} — {{ $settings['cafe_tagline'] ?? 'Every Cup Tells a Story' }}</title>
    <meta name="description" content="{{ $settings['cafe_description'] ?? 'Cafe modern dengan nuansa hangat.' }}">
    <meta name="keywords" content="cafe, kopi, coffee, {{ $settings['cafe_name'] ?? 'MyPOS' }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: {
                    cream:  {50:'#fdf8f0',100:'#faf0dc',200:'#f5deb3',300:'#e8c88a'},
                    coffee: {400:'#d4a864',500:'#b8894a',600:'#9a6e35',700:'#7d5628',800:'#5c3d1e',900:'#3d2810'}
                }
            }}
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .display { font-family: 'Playfair Display', serif; }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(32px)} to{opacity:1;transform:translateY(0)} }
        @keyframes float { 0%,100%{transform:translateY(0) rotate(0deg)} 50%{transform:translateY(-10px) rotate(3deg)} }
        .fade-in { animation: fadeInUp .7s ease both; }
        .float-anim { animation: float 4s ease-in-out infinite; }
        .hero-bg { background: linear-gradient(135deg, #3d2810 0%, #5c3d1e 40%, #9a6e35 100%); }
        .menu-card { transition: all .3s ease; }
        .menu-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(92,61,30,.15); }
        nav a { transition: color .2s; }

        /* Image container — fixed aspect ratio */
        .menu-img-wrap {
            position: relative;
            width: 100%;
            padding-top: 65%; /* ~3:2 ratio */
            overflow: hidden;
            background: linear-gradient(135deg, #faf0dc, #f5deb3);
        }
        .menu-img-wrap img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s ease;
        }
        .menu-card:hover .menu-img-wrap img { transform: scale(1.06); }
        .menu-img-wrap .menu-emoji {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        /* Skeleton shimmer untuk gambar loading */
        .img-skeleton {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, #f5deb3 25%, #faf0dc 50%, #f5deb3 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

        .cat-btn { transition: all .2s ease; }
        .cat-btn.active { background: #5c3d1e !important; color: #fff !important; box-shadow: 0 4px 12px rgba(92,61,30,.3); }
    </style>
</head>
<body class="bg-cream-50 overflow-x-hidden">

    {{-- Navbar --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-cream-100">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="#" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-coffee-700 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="font-bold text-coffee-800 text-lg">{{ $settings['cafe_name'] ?? 'MyPOS Cafe' }}</span>
            </a>
            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-coffee-600">
                <a href="#home" class="hover:text-coffee-800">Home</a>
                <a href="#about" class="hover:text-coffee-800">Tentang Kami</a>
                <a href="#menu" class="hover:text-coffee-800">Menu</a>
                <a href="#contact" class="hover:text-coffee-800">Kontak</a>
            </div>
            <a href="{{ route('login') }}" class="bg-coffee-700 hover:bg-coffee-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm hover:shadow-md">
                Login Admin
            </a>
        </div>
    </nav>

    {{-- Hero --}}
    <section id="home" class="hero-bg min-h-screen flex items-center relative overflow-hidden pt-16">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 400 400"><defs><pattern id="dots" width="30" height="30" patternUnits="userSpaceOnUse"><circle cx="15" cy="15" r="1" fill="white"/></pattern></defs><rect width="400" height="400" fill="url(#dots)"/></svg>
        </div>
        <div class="absolute top-20 right-16 text-5xl opacity-20 float-anim">☕</div>
        <div class="absolute bottom-32 right-1/4 text-4xl opacity-15 float-anim" style="animation-delay:1s">🫘</div>
        <div class="absolute top-1/3 right-1/3 text-3xl opacity-10 float-anim" style="animation-delay:2s">✨</div>

        <div class="max-w-6xl mx-auto px-6 py-20 relative z-10">
            <div class="max-w-2xl">
                <div class="fade-in inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-cream-200 text-xs font-semibold px-4 py-2 rounded-full mb-6 border border-white/20">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                    Buka Setiap Hari · 07.00 – 22.00
                </div>
                <h1 class="display text-5xl md:text-7xl font-black text-white leading-tight mb-6 fade-in" style="animation-delay:.1s">
                    {{ $settings['cafe_tagline'] ?? 'Every Cup Tells a Story' }}
                </h1>
                <p class="text-cream-200 text-lg leading-relaxed mb-10 fade-in" style="animation-delay:.2s">
                    {{ $settings['cafe_description'] ?? 'Nikmati pengalaman kopi terbaik di tempat yang nyaman. Setiap tegukan membawa cerita.' }}
                </p>
                <div class="flex gap-4 fade-in" style="animation-delay:.3s">
                    <a href="#menu" class="bg-cream-200 hover:bg-white text-coffee-800 font-bold px-8 py-3.5 rounded-xl transition-all shadow-lg">
                        Lihat Menu
                    </a>
                    <a href="#contact" class="border-2 border-white/40 hover:border-white text-white font-semibold px-8 py-3.5 rounded-xl transition-all">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- About --}}
    <section id="about" class="py-24 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <p class="text-coffee-500 font-semibold text-sm uppercase tracking-widest mb-3">Tentang Kami</p>
                    <h2 class="display text-4xl font-bold text-coffee-800 mb-6 leading-tight">Lebih dari Sekadar Secangkir Kopi</h2>
                    <p class="text-coffee-600 leading-relaxed mb-6">{{ $settings['cafe_description'] ?? 'Cafe modern dengan nuansa hangat, menyajikan kopi dan makanan berkualitas.' }}</p>
                    <div class="grid grid-cols-3 gap-4">
                        @foreach([['☕','Arabika Premium','Pilihan biji kopi terbaik'],['🌿','Bahan Segar','Disiapkan setiap hari'],['❤️','Penuh Cinta','Disajikan dengan hati']] as $f)
                        <div class="bg-cream-50 rounded-2xl p-4 text-center">
                            <div class="text-2xl mb-2">{{ $f[0] }}</div>
                            <p class="font-semibold text-coffee-700 text-xs">{{ $f[1] }}</p>
                            <p class="text-coffee-400 text-xs mt-1">{{ $f[2] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-gradient-to-br from-coffee-700 to-coffee-900 rounded-3xl p-10 text-white text-center shadow-2xl">
                        <div class="text-7xl mb-4 float-anim">☕</div>
                        <p class="display text-2xl font-bold mb-2">{{ $settings['cafe_name'] ?? 'MyPOS Cafe' }}</p>
                        <p class="text-coffee-200 text-sm">Temukan kenyamanan dalam setiap kunjungan</p>
                    </div>
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-cream-200 rounded-2xl -z-10"></div>
                    <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-coffee-200 rounded-2xl -z-10"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Menu --}}
    <section id="menu" class="py-24 bg-cream-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-14">
                <p class="text-coffee-500 font-semibold text-sm uppercase tracking-widest mb-3">Pilihan Kami</p>
                <h2 class="display text-4xl font-bold text-coffee-800 mb-4">Menu Favorit</h2>
                <p class="text-coffee-400 max-w-md mx-auto">Dari kopi spesialti hingga makanan lezat, semua tersedia untuk Anda</p>
            </div>

            {{-- Category filter --}}
            <div class="flex flex-wrap gap-2 justify-center mb-10" id="cat-filter">
                <button onclick="filterMenu('all')" data-cat="all"
                    class="cat-btn active px-5 py-2 rounded-full text-sm font-semibold bg-coffee-700 text-white">
                    ☕ Semua
                </button>
                @foreach($categories as $cat)
                @if($cat->slug !== 'all')
                <button onclick="filterMenu('{{ $cat->slug }}')" data-cat="{{ $cat->slug }}"
                    class="cat-btn px-5 py-2 rounded-full text-sm font-semibold bg-white text-coffee-600 hover:bg-coffee-100 border border-cream-200">
                    {{ $cat->icon }} {{ $cat->name }}
                </button>
                @endif
                @endforeach
            </div>

            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="menu-grid">
                @foreach($menus as $menu)
                @php
                    // Smart image resolution:
                    // 1. Jika image adalah URL (http/https) — pakai langsung (Unsplash, dll)
                    // 2. Jika image adalah path lokal — pakai asset('storage/...')
                    // 3. Jika kosong — tampilkan emoji kategori
                    $imgSrc = null;
                    if ($menu->image) {
                        if (str_starts_with($menu->image, 'http://') || str_starts_with($menu->image, 'https://')) {
                            $imgSrc = $menu->image;
                        } else {
                            $imgSrc = asset('storage/' . $menu->image);
                        }
                    }
                    $fallbackEmoji = $menu->category->icon ?? '☕';
                @endphp
                <div class="menu-card bg-white rounded-2xl shadow-sm border border-cream-100 overflow-hidden"
                     data-cat="{{ $menu->category->slug }}">

                    {{-- Image area --}}
                    <div class="menu-img-wrap">
                        @if($imgSrc)
                            {{-- Skeleton shimmer ditampilkan saat gambar loading --}}
                            <div class="img-skeleton" id="sk-{{ $menu->id }}"></div>
                            <img
                                src="{{ $imgSrc }}"
                                alt="{{ $menu->name }}"
                                loading="lazy"
                                onload="this.previousElementSibling.style.display='none'"
                                onerror="this.style.display='none';this.previousElementSibling.style.display='none';document.getElementById('em-{{ $menu->id }}').style.display='flex'"
                            >
                            <div class="menu-emoji" id="em-{{ $menu->id }}" style="display:none">{{ $fallbackEmoji }}</div>
                        @else
                            <div class="menu-emoji">{{ $fallbackEmoji }}</div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-xs text-coffee-400 font-medium">{{ $menu->category->name }}</p>
                            @if(!$menu->is_available)
                            <span style="font-size:10px;background:#fee2e2;color:#991b1b;padding:2px 8px;border-radius:99px;font-weight:700;">Habis</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-coffee-800 mb-1 leading-snug">{{ $menu->name }}</h3>
                        @if($menu->description)
                            <p class="text-xs text-coffee-400 mb-3 leading-relaxed">{{ Str::limit($menu->description, 65) }}</p>
                        @endif
                        <p class="text-coffee-700 font-bold text-sm">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Empty state --}}
            @if($menus->isEmpty())
            <div class="text-center py-20 text-coffee-300">
                <span class="text-5xl block mb-4">☕</span>
                <p class="font-medium">Menu sedang diperbarui. Kembali lagi segera!</p>
            </div>
            @endif
        </div>
    </section>

    {{-- Contact --}}
    <section id="contact" class="py-24 bg-coffee-800 text-white">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <p class="text-coffee-300 font-semibold text-sm uppercase tracking-widest mb-3">Hubungi Kami</p>
            <h2 class="display text-4xl font-bold mb-12">Temukan Kami</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach([
                    ['📍','Lokasi', $settings['cafe_address'] ?? 'Jl. Kopi Enak No.1'],
                    ['📞','Telepon', $settings['cafe_phone'] ?? '08123456789'],
                    ['✉️','Email',   $settings['cafe_email'] ?? 'hello@cafe.com']
                ] as $c)
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 hover:bg-white/15 transition-all">
                    <div class="text-3xl mb-3">{{ $c[0] }}</div>
                    <p class="font-semibold mb-1">{{ $c[1] }}</p>
                    <p class="text-coffee-300 text-sm">{{ $c[2] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-coffee-900 text-coffee-400 py-8 text-center text-sm">
        <p>© {{ date('Y') }} {{ $settings['cafe_name'] ?? 'MyPOS Cafe' }}. Powered by <strong class="text-coffee-300">MyPOS</strong></p>
    </footer>

    <script>
        function filterMenu(cat) {
            // Update button styles
            document.querySelectorAll('.cat-btn').forEach(btn => {
                const isActive = btn.dataset.cat === cat;
                btn.classList.toggle('active', isActive);
                btn.classList.toggle('bg-white', !isActive);
                btn.classList.toggle('text-coffee-600', !isActive);
            });

            // Show/hide cards dengan fade
            document.querySelectorAll('#menu-grid .menu-card').forEach(card => {
                const show = cat === 'all' || card.dataset.cat === cat;
                if (show) {
                    card.style.display = 'block';
                    card.style.opacity = '0';
                    requestAnimationFrame(() => {
                        card.style.transition = 'opacity .25s ease';
                        card.style.opacity = '1';
                    });
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Smooth scroll for nav links
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
