<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Buku Induk') - SD Muhammadiyah Gisting</title>
    <!-- Modern Font: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0ea5e9;
            --light-blue: #e0f2fe;
            --sky-blue: #38bdf8;
            --accent-yellow: #facc15;
            --light-yellow: #fef08a;
            --dark-blue: #0c4a6e;
            --text-gray: #374151;
            --light-gray: #f3f4f6;
            --glass-bg: rgba(255, 255, 255, 0.75);
            --glass-border: rgba(255, 255, 255, 0.6);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: var(--text-gray);
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Abstract Animated Background */
        .bg-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            overflow: hidden;
        }

        .blob {
            position: absolute;
            filter: blur(90px);
            z-index: -1;
            opacity: 0.6;
            animation: moveBlob 20s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 50%;
        }

        .blob-1 {
            top: -10%;
            left: -10%;
            width: 50vw;
            height: 50vw;
            background: var(--sky-blue);
            animation-duration: 25s;
        }

        .blob-2 {
            bottom: -20%;
            right: -10%;
            width: 60vw;
            height: 60vw;
            background: var(--accent-yellow);
            animation-duration: 22s;
            animation-delay: -5s;
        }

        .blob-3 {
            top: 40%;
            left: 30%;
            width: 40vw;
            height: 40vw;
            background: #cbd5e1;
            animation-duration: 28s;
            animation-delay: -10s;
        }

        @keyframes moveBlob {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(15vw, -15vh) scale(1.1); }
            66% { transform: translate(-10vw, 20vh) scale(0.9); }
            100% { transform: translate(0, 0) scale(1); }
        }

        /* Navigation */
        header {
            width: 100%;
            padding: 1.5rem 0;
            position: relative;
            z-index: 100;
        }

        nav {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--dark-blue);
            z-index: 60;
        }

        .logo h2 {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        .logo span {
            color: var(--primary-blue);
        }

        .logo-icon {
            background: linear-gradient(135deg, var(--primary-blue), var(--sky-blue));
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 4px 10px rgba(14, 165, 233, 0.3);
        }

        .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-gray);
            font-weight: 600;
            margin-left: 2rem;
            transition: color 0.3s ease;
        }

        .nav-links a.active, .nav-links a:hover {
            color: var(--primary-blue);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--accent-yellow), #eab308);
            color: #422006 !important;
            padding: 0.6rem 1.75rem;
            border-radius: 50px;
            font-weight: 700 !important;
            box-shadow: 0 4px 14px rgba(250, 204, 21, 0.4);
            transition: all 0.3s ease !important;
            border: 2px solid transparent;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(250, 204, 21, 0.6);
            border-color: rgba(255,255,255,0.5);
        }

        /* Mobile Nav replacement */
        .mobile-nav-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-blue);
            cursor: pointer;
            z-index: 60;
            position: relative;
        }
        
        @media (max-width: 768px) {
            .mobile-nav-btn {
                display: block;
            }
            .nav-links {
                display: none;
            }
            nav {
                padding: 0 1.5rem;
            }
        }

        .mobile-nav-menu {
            display: flex;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(15px);
            z-index: 50;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            transform: translateY(-100%);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            opacity: 0;
            pointer-events: none;
        }

        .mobile-nav-menu.active {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .mobile-nav-menu a {
            text-decoration: none;
            color: var(--text-gray);
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }
        
        .mobile-nav-menu a.active {
            color: var(--primary-blue);
        }

        .mobile-nav-menu a.btn-login {
            background: linear-gradient(135deg, var(--accent-yellow), #eab308);
            border-bottom: none;
            text-align: center;
            padding: 1rem 3rem;
            border-radius: 50px;
            font-size: 1.25rem;
            margin-top: 1rem;
            color: #422006 !important;
        }

        /* Reusable components for inner pages */
        .page-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 1;
            width: 100%;
        }

        .glass-container {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 2rem;
            padding: 3rem;
            max-width: 1280px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
            animation: slideUp 1s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Inherit the hero specific CSS to child */
        @yield('styles')
        
        [x-cloak] { display: none !important; }

        /* Toast Tailwind Compatibility for Landing */
        .fixed { position: fixed; }
        .z-50 { z-index: 50; }
        .bottom-8 { bottom: 2rem; }
        .right-8 { right: 2rem; }
        .bg-slate-800 { background-color: #1e293b; }
        .text-white { color: #fff; }
        .px-5 { padding-left: 1.25rem; padding-right: 1.25rem; }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        .rounded-2xl { border-radius: 1rem; }
        .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .gap-3 { gap: 0.75rem; }
        .font-semibold { font-weight: 600; }
        .text-sm { font-size: 0.875rem; }
        .border { border: 1px solid transparent; }
        .border-white\/10 { border-color: rgba(255, 255, 255, 0.1); }
        .flex-shrink-0 { flex-shrink: 0; }
        .bg-emerald-500 { background-color: #10b981; }
        .rounded-full { border-radius: 9999px; }
        .p-1.5 { padding: 0.375rem; }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .shadow-emerald-500\/20 { box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2); }
        .w-4 { width: 1rem; }
        .h-4 { height: 1rem; }
        .w-5 { width: 1.25rem; }
        .h-5 { height: 1.25rem; }
        .ml-4 { margin-left: 1rem; }
        .text-slate-400 { color: #94a3b8; }
        .cursor-pointer { cursor: pointer; }
        .focus\:outline-none:focus { outline: none; }
        .transition-colors { transition-property: color; transition-duration: 150ms; }
        .hover\:text-white:hover { color: #fff; }
        .transition { transition-all duration-150ms; }
        .duration-300 { transition-duration: 300ms; }
        .duration-200 { transition-duration: 200ms; }
        .ease-out { transition-timing-function: cubic-bezier(0, 0, 0.2, 1); }
        .ease-in { transition-timing-function: cubic-bezier(0.4, 0, 1, 1); }
        .opacity-0 { opacity: 0; }
        .opacity-100 { opacity: 1; }
        .translate-y-5 { transform: translateY(1.25rem); }
        .translate-y-0 { transform: translateY(0); }
    </style>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-container">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <!-- Header Navigation -->
    <header>
        <nav>
            <a href="{{ url('/') }}" class="logo">
                <div class="logo-icon">BI</div>
                <h2>Buku Induk<span>.</span></h2>
            </a>
            
            <div class="nav-links">
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Beranda</a>
                <a href="{{ url('/tentang') }}" class="{{ request()->is('tentang') ? 'active' : '' }}">Tentang</a>
                <a href="{{ url('/kontak') }}" class="{{ request()->is('kontak') ? 'active' : '' }}">Kontak</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-login">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-login">Login</a>
                    @endauth
                @endif
            </div>

            <button class="mobile-nav-btn" onclick="toggleMobileMenu()" id="menuBtn">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="12" x2="20" y2="12"></line>
                    <line x1="4" y1="6" x2="20" y2="6"></line>
                    <line x1="4" y1="18" x2="20" y2="18"></line>
                </svg>
            </button>
        </nav>
        
        <div class="mobile-nav-menu" id="mobileMenu">
            <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Beranda</a>
            <a href="{{ url('/tentang') }}" class="{{ request()->is('tentang') ? 'active' : '' }}">Tentang</a>
            <a href="{{ url('/kontak') }}" class="{{ request()->is('kontak') ? 'active' : '' }}">Kontak</a>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-login">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-login">Login</a>
                @endauth
            @endif
        </div>
    </header>

    @yield('content')

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const btn = document.getElementById('menuBtn');
            const isActive = menu.classList.contains('active');
            
            if (isActive) {
                menu.classList.remove('active');
                btn.innerHTML = `<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="12" x2="20" y2="12"></line><line x1="4" y1="6" x2="20" y2="6"></line><line x1="4" y1="18" x2="20" y2="18"></line></svg>`;
            } else {
                menu.classList.add('active');
                btn.innerHTML = `<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`;
            }
        }
    </script>

    {{-- Global Toast Notification --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-5"
         class="fixed z-50 bottom-8 right-8 bg-slate-800 text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3 font-semibold text-sm border border-white/10"
         x-cloak>
        <div class="flex-shrink-0 bg-emerald-500 rounded-full p-1.5 shadow-lg shadow-emerald-500/20"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>
        <p>{{ session('success') }}</p>
        <button type="button" @click="show = false" class="ml-4 text-slate-400 hover:text-white transition-colors cursor-pointer"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    @endif
</body>
</html>
