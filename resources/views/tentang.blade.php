@extends('layouts.landing')

@section('title', 'Tentang Aplikasi')

@section('styles')
<style>
    .about-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    .about-header h1 {
        font-size: 3rem;
        font-weight: 800;
        color: var(--dark-blue);
        margin-bottom: 1rem;
        letter-spacing: -1px;
    }
    .about-header h1 span {
        color: var(--primary-blue);
    }
    .about-header p {
        font-size: 1.25rem;
        color: var(--text-gray);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }
    .about-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }
    .about-card {
        background: rgba(255, 255, 255, 0.8);
        border: 1px solid var(--glass-border);
        padding: 2.5rem 2rem;
        border-radius: 1.5rem;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .about-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.05);
    }
    .card-icon {
        width: 60px;
        height: 60px;
        background: var(--light-blue);
        color: var(--primary-blue);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 1.5rem;
    }
    .about-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-blue);
        margin-bottom: 1rem;
    }
    .about-card p {
        color: var(--text-gray);
        line-height: 1.6;
    }
    @media (max-width: 900px) {
        .about-grid { grid-template-columns: 1fr; }
        .about-header h1 { font-size: 2.5rem; }
    }
</style>
@endsection

@section('content')
<main class="page-wrapper">
    <div class="glass-container">
        <div class="about-header">
            <h1>Tentang <span>Buku Induk</span></h1>
            <p>Sistem pencatatan dan pengelolaan data induk siswa modern yang dioptimalkan untuk kebutuhan SD Muhammadiyah Gisting.</p>
        </div>

        <div class="about-grid">
            <div class="about-card">
                <div class="card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <h3>Aman & Terpusat</h3>
                <p>Semua data identitas, akademik, dan riwayat siswa tersimpan dengan aman pada pangkalan data terpusat, meminimalisir risiko kehilangan data.</p>
            </div>
            
            <div class="about-card" style="background: rgba(254, 240, 138, 0.3); border-color: rgba(250, 204, 21, 0.4);">
                <div class="card-icon" style="background: var(--light-yellow); color: #b45309;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                </div>
                <h3>Akses Instan</h3>
                <p>Pencarian, pembaruan, dan pelaporan informasi siswa bisa dilakukan kapan saja, mengefisienkan seluruh proses administrasi.</p>
            </div>
            
            <div class="about-card">
                <div class="card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                </div>
                <h3>Antarmuka Intuitif</h3>
                <p>Desain aplikasi yang modern, nyaman dipandang, dan mudah dipahami, memberikan pengalaman penggunaan terbaik bagi staf pendidik tata usaha.</p>
            </div>
        </div>
    </div>
</main>
@endsection
