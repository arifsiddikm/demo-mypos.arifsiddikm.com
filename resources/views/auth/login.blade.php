<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — MyPOS</title>
    <meta name="description" content="Login ke MyPOS Cafe Management System">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: {
                    cream: { 50:'#fdf8f0',100:'#faf0dc',200:'#f5deb3' },
                    coffee: { 400:'#d4a864',500:'#b8894a',600:'#9a6e35',700:'#7d5628',800:'#5c3d1e' }
                }
            }}
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
        .float { animation: float 4s ease-in-out infinite; }
        .fade-in { animation: fadeInUp 0.6s ease both; }
        .delay-1 { animation-delay:0.1s }
        .delay-2 { animation-delay:0.2s }
        .delay-3 { animation-delay:0.3s }
        .delay-4 { animation-delay:0.4s }
    </style>
</head>
<body class="min-h-screen bg-cream-50 flex">

    {{-- Left decorative panel --}}
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-coffee-800 via-coffee-700 to-coffee-600 relative overflow-hidden flex-col items-center justify-center p-12 text-white">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                <defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1.5" fill="white"/></pattern></defs>
                <rect width="400" height="400" fill="url(#grid)"/>
            </svg>
        </div>
        <div class="absolute top-10 right-10 w-32 h-32 bg-white opacity-5 rounded-full"></div>
        <div class="absolute bottom-20 left-5 w-48 h-48 bg-coffee-500 opacity-30 rounded-full"></div>

        <div class="relative z-10 text-center">
            <div class="float mb-8 inline-flex items-center justify-center w-24 h-24 bg-white bg-opacity-20 rounded-3xl backdrop-blur-sm shadow-2xl">
                <svg class="w-14 h-14 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-extrabold mb-3">MyPOS</h1>
            <p class="text-coffee-200 text-lg mb-8">Cafe Management System</p>

            <div class="space-y-4 text-left max-w-xs mx-auto">
                @foreach(['POS Kasir modern & cepat','Manajemen menu & inventory','Laporan transaksi lengkap','Multi-metode pembayaran'] as $f)
                <div class="flex items-center gap-3 bg-white bg-opacity-10 rounded-xl px-4 py-2.5">
                    <div class="w-2 h-2 bg-coffee-300 rounded-full flex-shrink-0"></div>
                    <span class="text-sm text-coffee-100">{{ $f }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="absolute top-1/4 left-8 text-3xl opacity-30" style="animation:float 5s ease-in-out infinite">☕</div>
        <div class="absolute bottom-1/4 right-8 text-2xl opacity-20" style="animation:float 6s ease-in-out infinite 1s">🫘</div>
        <div class="absolute top-3/4 left-1/4 text-xl opacity-20" style="animation:float 7s ease-in-out infinite 2s">✨</div>
    </div>

    {{-- Right login form --}}
    <div class="flex-1 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            {{-- Mobile logo --}}
            <div class="lg:hidden text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-coffee-700 rounded-2xl shadow-lg mb-3">
                    <svg class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-coffee-800">MyPOS</h1>
            </div>

            <div class="fade-in">
                <h2 class="text-2xl font-bold text-coffee-800 mb-1">Selamat datang kembali</h2>
                <p class="text-coffee-400 text-sm mb-8">Masuk ke panel manajemen cafe Anda</p>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div class="fade-in delay-1">
                        <label class="block text-sm font-semibold text-coffee-700 mb-1.5">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                            class="w-full px-4 py-3 rounded-xl border border-cream-200 bg-cream-50 focus:outline-none focus:ring-2 focus:ring-coffee-400 focus:border-transparent transition-all text-coffee-800"
                            placeholder="admin@mypos.com">
                    </div>

                    <div class="fade-in delay-2">
                        <label class="block text-sm font-semibold text-coffee-700 mb-1.5">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required autocomplete="current-password"
                                class="w-full px-4 py-3 rounded-xl border border-cream-200 bg-cream-50 focus:outline-none focus:ring-2 focus:ring-coffee-400 focus:border-transparent transition-all text-coffee-800 pr-12"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePass()" class="absolute right-3 top-1/2 -translate-y-1/2 text-coffee-400 hover:text-coffee-600">
                                <svg id="eye-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="fade-in delay-3 flex items-center gap-3">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-cream-300 text-coffee-600 focus:ring-coffee-400">
                        <label for="remember" class="text-sm text-coffee-600">Ingat saya</label>
                    </div>

                    <button type="submit" class="w-full bg-coffee-700 hover:bg-coffee-800 text-white font-bold py-3.5 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm">
                        Masuk ke Dashboard
                    </button>

                    {{-- Autofill buttons — testing quick login --}}
                    <div class="fade-in delay-4">
                        <p class="text-xs text-center text-coffee-300 mb-2 font-medium">⚡ Quick Login (Testing)</p>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" onclick="autofill('admin@mypos.com','password','👑 Admin')"
                                class="border-2 border-coffee-200 text-coffee-700 hover:bg-coffee-50 font-semibold py-2.5 rounded-xl transition-all duration-200 text-xs flex items-center justify-center gap-1.5">
                                <span>👑</span> Admin
                            </button>
                            <button type="button" onclick="autofill('kasir@mypos.com','password','🧑‍💼 Kasir')"
                                class="border-2 border-cream-200 text-coffee-600 hover:bg-cream-50 font-semibold py-2.5 rounded-xl transition-all duration-200 text-xs flex items-center justify-center gap-1.5">
                                <span>🧑‍💼</span> Kasir
                            </button>
                        </div>
                    </div>
                </form>

                <p class="text-center text-xs text-coffee-300 mt-6">
                    <a href="{{ route('landing') }}" class="hover:text-coffee-500 transition-colors">← Kembali ke halaman utama</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function autofill(email, pass, label) {
            document.getElementById('email').value    = email;
            document.getElementById('password').value = pass;
            ['email','password'].forEach(id => {
                const el = document.getElementById(id);
                el.classList.add('ring-2','ring-coffee-400');
                setTimeout(() => el.classList.remove('ring-2','ring-coffee-400'), 1500);
            });
        }

        function togglePass() {
            const p = document.getElementById('password');
            p.type = p.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
