@extends('layouts.landing')

@section('title', 'Hubungi Kami')

@section('styles')
    .contact-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }
    .contact-info h1 {
        font-size: 3rem;
        font-weight: 800;
        color: var(--dark-blue);
        margin-bottom: 1rem;
        letter-spacing: -1px;
    }
    .contact-info p {
        font-size: 1.15rem;
        color: var(--text-gray);
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }
    .info-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }
    .info-icon {
        width: 45px;
        height: 45px;
        background: var(--light-blue);
        color: var(--primary-blue);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .info-content h4 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dark-blue);
        margin-bottom: 0.25rem;
    }
    .info-content p {
        font-size: 1rem;
        color: var(--text-gray);
        margin-bottom: 0;
    }
    
    .contact-form {
        background: rgba(255, 255, 255, 0.9);
        padding: 2.5rem;
        border-radius: 1.5rem;
        border: 1px solid var(--glass-border);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        display: block;
        font-weight: 600;
        color: var(--dark-blue);
        margin-bottom: 0.5rem;
    }
    .form-control {
        width: 100%;
        padding: 0.85rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        font-family: inherit;
        font-size: 1rem;
        background: #f8fafc;
        transition: all 0.2s ease;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--primary-blue);
        background: white;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
    }
    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }
    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, var(--accent-yellow), #eab308);
        color: #422006;
        padding: 1rem;
        border: none;
        border-radius: 0.75rem;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 14px rgba(250, 204, 21, 0.4);
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(250, 204, 21, 0.6);
    }

    @media (max-width: 900px) {
        .contact-wrapper { grid-template-columns: 1fr; gap: 3rem; }
        .contact-info h1 { font-size: 2.5rem; }
    }
@endsection

@section('content')
<main class="page-wrapper">
    <div class="glass-container">
        <div class="contact-wrapper">
            <!-- Info Kolom Kiri -->
            <div class="contact-info">
                <h1>{!! \App\Models\Setting::getValue('landing_contact_title', 'Hubungi <span>Kami</span>') !!}</h1>
                <p>{{ \App\Models\Setting::getValue('landing_contact_subtitle', 'Ada kendala tentang aplikasi atau perihal administratif data siswa? Tim administrasi SD Muhammadiyah Gisting siap membantu.') }}</p>
                
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <div class="info-content">
                            <h4>Lokasi Sekolah</h4>
                            <p>{{ \App\Models\Setting::getValue('landing_contact_address', 'Jl. Raya Gisting Raya No. 1, Kabupaten Tanggamus, Lampung') }}</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon" style="background: var(--light-yellow); color: #b45309;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <div class="info-content">
                            <h4>Telepon Utama</h4>
                            <p>{{ \App\Models\Setting::getValue('landing_contact_phone', '(0722) 123456') }}</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                        <div class="info-content">
                            <h4>Email Resmi</h4>
                            <p>{{ \App\Models\Setting::getValue('landing_contact_email', 'info@sdmuhammadiyahgisting.sch.id') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Kolom Kanan -->
            <div class="contact-form" x-data="{ sending: false }">
                @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-6 rounded-2xl mb-6 flex items-center gap-4 animate-in fade-in slide-in-from-bottom-2">
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 text-emerald-600">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg">Pesan Terkirim!</h4>
                        <p class="text-emerald-600/80 text-sm">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" @submit="sending = true">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Masukkan nama..." required>
                    </div>
                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email..." required>
                    </div>
                    <div class="form-group">
                        <label for="message">Pesan / Pertanyaan</label>
                        <textarea id="message" name="message" class="form-control" placeholder="Tulis tujuan Anda di sini..." required></textarea>
                    </div>
                    <button type="submit" class="btn-submit" :disabled="sending" :class="sending ? 'opacity-70 cursor-not-allowed' : ''">
                        <span x-show="!sending">Kirim Pesan</span>
                        <span x-show="sending">Mengirim...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
