@extends('layouts.landing')

@section('title', 'Beranda')

@section('styles')
<style>
    /* Hero Section Specific */
    .hero {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 0;
    }

    .hero-card {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 4rem;
        align-items: center;
        padding: 4rem;
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
        font-size: 1.25rem;
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
        padding: 1.1rem 2.2rem;
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
        padding: 1.1rem 2.2rem;
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

    /* Hero Image */
    .hero-image {
        position: relative;
        transform-style: preserve-3d;
        perspective: 1000px;
    }

    .image-card {
        width: 100%;
        height: 500px;
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

    @media (max-width: 1024px) {
        .hero-card {
            grid-template-columns: 1fr;
            padding: 3rem;
            text-align: center;
        }
        .hero-text {
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
        .image-card { height: 400px; }
    }

    @media (max-width: 768px) {
        .hero-card { padding: 2rem 1.5rem; }
        .hero-text h1 { font-size: 2.5rem; }
        .badge-1 { left: -20px; top: 20px; }
        .badge-2 { right: -10px; bottom: 20px; }
    }

    @media (max-width: 480px) {
        .hero-text h1 { font-size: 2rem; }
        .cta-group { flex-direction: column; width: 100%; }
        .btn-primary, .btn-secondary { width: 100%; }
        .image-card { height: 300px; }
        .floating-badge { display: none; }
    }
</style>
@endsection

@section('content')
<main class="page-wrapper hero">
    <div class="glass-container hero-card">
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
                            Login Sistem
                            <svg style="margin-left: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                        </a>
                    @endauth
                @endif
                <a href="{{ url('/tentang') }}" class="btn-secondary">Pelajari Lebih Lanjut</a>
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
</main>
@endsection
