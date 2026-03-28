<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buku Induk - SD Muhammadiyah Gisting</title>
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
            z-index: 10;
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

        .nav-links a:hover {
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

        /* Hero Section */
        .hero {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 100px);
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 2rem;
            padding: 3rem;
            max-width: 1280px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
            animation: slideUp 1s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            color: var(--dark-blue);
            letter-spacing: -1px;
        }
        
        .hero-text h1 span.highlight-blue {
            color: var(--primary-blue);
        }

        .hero-text h1 span.highlight-yellow {
            position: relative;
            display: inline-block;
        }

        .hero-text h1 span.highlight-yellow::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 100%;
            height: 12px;
            background-color: var(--accent-yellow);
            z-index: -1;
            border-radius: 4px;
            opacity: 0.8;
            transform: rotate(-1deg);
        }

        .hero-text p {
            font-size: 1.2rem;
            color: #4b5563;
            margin-bottom: 2.5rem;
            line-height: 1.6;
            max-width: 90%;
        }

        .cta-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-blue), var(--sky-blue));
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.5);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.8);
            color: var(--dark-blue);
            border: 1px solid rgba(226, 232, 240, 0.8);
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .btn-secondary:hover {
            background-color: white;
            border-color: var(--primary-blue);
            color: var(--primary-blue);
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
        }

        /* Hero Image & Animations */
        .hero-image {
            position: relative;
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .image-card {
            width: 100%;
            height: 480px;
            border-radius: 2rem;
            border: 8px solid white;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1), 0 0 0 1px rgba(0,0,0,0.05);
            overflow: hidden;
            animation: floatImage 8s ease-in-out infinite;
            position: relative;
        }

        .image-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .image-card:hover img {
            transform: scale(1.05);
        }

        .floating-badge {
            position: absolute;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 1.5rem;
            border-radius: 1.25rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid rgba(255,255,255,0.7);
            z-index: 2;
        }

        .badge-1 {
            top: 40px;
            left: -40px;
            animation: floatBadge 6s ease-in-out infinite alternate;
        }

        .badge-2 {
            bottom: 50px;
            right: -30px;
            animation: floatBadge 8s ease-in-out infinite alternate-reverse;
        }

        .badge-icon {
            width: 48px;
            height: 48px;
            background: var(--light-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-size: 1.5rem;
        }
        
        .badge-2 .badge-icon {
            background: var(--light-yellow);
            color: #b45309;
        }

        .badge-text {
            display: flex;
            flex-direction: column;
        }
        
        .badge-text strong {
            color: var(--dark-blue);
            font-size: 1.1rem;
        }
        
        .badge-text span {
            color: var(--text-gray);
            font-size: 0.85rem;
        }

        @keyframes floatImage {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        @keyframes floatBadge {
            0% { transform: translateY(0); }
            100% { transform: translateY(-20px); }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .glass-card {
                grid-template-columns: 1fr;
                padding: 3rem;
                gap: 3rem;
            }

            .hero-text {
                text-align: center;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            
            .hero-text p {
                max-width: 100%;
            }

            .cta-group {
                justify-content: center;
            }

            .image-card {
                height: 400px;
            }
        }

        @media (max-width: 768px) {
            nav {
                padding: 0 1.5rem;
            }
            
            .hero {
                padding: 1rem;
            }

            .glass-card {
                padding: 2rem 1.5rem;
            }
            
            .hero-text h1 {
                font-size: 2.5rem;
            }

            .nav-links {
                display: none;
            }
            
            /* Mobile Nav replacement */
            .mobile-nav-btn {
                display: block;
                background: none;
                border: none;
                font-size: 1.5rem;
                color: var(--dark-blue);
                cursor: pointer;
            }
            
            .floating-badge {
                transform: scale(0.85);
            }
            
            .badge-1 {
                left: -20px;
                top: 20px;
            }
            
            .badge-2 {
                right: -10px;
                bottom: 20px;
            }
        }

        @media (max-width: 480px) {
            .hero-text h1 {
                font-size: 2rem;
            }
            
            .cta-group {
                flex-direction: column;
                width: 100%;
            }
            
            .btn-primary, .btn-secondary {
                width: 100%;
            }
            
            .image-card {
                height: 300px;
            }
            
            .floating-badge {
                display: none;
            }
        }
        
        .mobile-nav-btn {
            display: none;
        }
        @media (max-width: 768px) {
            .mobile-nav-btn {
                display: block;
            }
        }
    </style>
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
            <a href="/" class="logo">
                <div class="logo-icon">BI</div>
                <h2>Buku Induk<span>.</span></h2>
            </a>
            
            <div class="nav-links">
                <a href="#">Beranda</a>
                <a href="#">Tentang</a>
                <a href="#">Kontak</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-login">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-login">Login</a>
                    @endauth
                @endif
            </div>

            <button class="mobile-nav-btn text-[#0c4a6e]">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="glass-card">
            <!-- Left Text Content -->
            <div class="hero-text">
                <h1>Sistem <span class="highlight-blue">Informasi</span> <br> <span class="highlight-yellow">Buku Induk</span></h1>
                <p>Platform digital modern untuk mengelola data induk siswa SD Muhammadiyah Gisting. Aman, cepat, dan mudah digunakan.</p>
                
                <div class="cta-group">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary">
                                Akses Dashboard
                                <svg style="margin-left: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary">
                                Mulai Sekarang
                                <svg style="margin-left: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </a>
                        @endauth
                    @endif
                    <a href="#" class="btn-secondary">Pelajari Lebih Lanjut</a>
                </div>
            </div>

            <!-- Right Image Content -->
            <div class="hero-image">
                <div class="badge-1 floating-badge">
                    <div class="badge-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <div class="badge-text">
                        <strong>Data Aman</strong>
                        <span>Tersimpan di Cloud</span>
                    </div>
                </div>

                <div class="image-card">
                    <img src="{{ asset('images/hero.png') }}" alt="SD Muhammadiyah Gisting Students">
                </div>

                <div class="badge-2 floating-badge">
                    <div class="badge-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div class="badge-text">
                        <strong>Akses Cepat</strong>
                        <span>24/7 Tersedia</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
