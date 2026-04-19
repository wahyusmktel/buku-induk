@extends('layouts.app')

@section('title', 'Pengaturan Laman')
@section('header_title', 'Pengaturan Laman Laman')

@section('content')
<div x-data="{ tab: '{{ request()->query('tab', 'welcome') }}' }" class="space-y-6">
    {{-- Tab Navigation --}}
    <div class="flex items-center gap-2 p-1 bg-slate-100 rounded-2xl w-fit">
        <button @click="tab = 'welcome'" :class="tab === 'welcome' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all cursor-pointer">
            Beranda (Welcome)
        </button>
        <button @click="tab = 'about'" :class="tab === 'about' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all cursor-pointer">
            Tentang
        </button>
        <button @click="tab = 'contact'" :class="tab === 'contact' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all cursor-pointer">
            Kontak
        </button>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Tab: Welcome --}}
    <div x-show="tab === 'welcome'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" x-cloak>
        <form action="{{ route('settings.pages.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="active_tab" value="welcome">
            
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800">Landing Page: Hero Section</h3>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition-all">Simpan Laman Beranda</button>
                </div>
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {{-- Editor Judul Hero --}}
                        <div class="space-y-4">
                            <label class="text-sm font-bold text-slate-700">Penyusun Judul Hero</label>
                            
                            <div class="grid grid-cols-1 gap-4 p-5 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Baris 1: Teks Awal</label>
                                    <input type="text" name="hero_title_p1" value="{{ $settings['hero_title_p1'] ?? 'Sistem' }}" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Baris 1: Highlight Biru</label>
                                    <input type="text" name="hero_title_blue" value="{{ $settings['hero_title_blue'] ?? 'Informasi' }}" class="w-full bg-white border border-indigo-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Baris 2: Teks Antara (Opsional)</label>
                                    <input type="text" name="hero_title_p2" value="{{ $settings['hero_title_p2'] ?? '' }}" placeholder="Kosongkan jika tidak ada baris baru..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-amber-500 uppercase tracking-widest">Baris 2: Highlight Kuning (Garis Bawah)</label>
                                    <input type="text" name="hero_title_yellow" value="{{ $settings['hero_title_yellow'] ?? 'Buku Induk' }}" class="w-full bg-white border border-amber-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-600">Subjudul Hero</label>
                                <textarea name="landing_hero_subtitle" rows="3" class="w-full bg-white border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">{{ $settings['landing_hero_subtitle'] ?? 'Platform digital modern untuk mengelola data induk siswa SD Muhammadiyah Gisting. Aman, cepat, dan mudah digunakan.' }}</textarea>
                            </div>
                        </div>

                        {{-- Image Dropzone --}}
                        <div x-data="{ 
                            dragging: false, 
                            preview: '{{ isset($settings['landing_hero_image']) ? asset('storage/' . $settings['landing_hero_image']) : asset('images/hero.png') }}' 
                        }" 
                             class="space-y-4">
                            <label class="text-sm font-bold text-slate-700 block">Hero Image (Utama)</label>
                            
                            <div @dragover.prevent="dragging = true" 
                                 @dragleave.prevent="dragging = false" 
                                 @drop.prevent="dragging = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'))"
                                 class="relative w-full min-h-[280px] border-2 border-dashed rounded-[2rem] transition-all flex flex-col items-center justify-center p-4"
                                 :class="dragging ? 'border-indigo-500 bg-indigo-50/50' : 'border-slate-200 bg-slate-50'">
                                
                                <div class="relative w-full h-[250px] rounded-2xl overflow-hidden group">
                                    <img :src="preview" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2">
                                        <button @click="$refs.fileInput.click()" type="button" class="px-5 py-2.5 bg-white text-indigo-600 rounded-xl font-bold text-xs shadow-xl">Ganti Gambar</button>
                                        <p class="text-[10px] text-white/80 font-medium">atau seret file ke sini</p>
                                    </div>
                                </div>
                                
                                <input type="file" x-ref="fileInput" name="landing_hero_image" class="hidden" @change="let file = $event.target.files[0]; if (file) { let reader = new FileReader(); reader.onload = (e) => preview = e.target.result; reader.readAsDataURL(file); }">
                            </div>
                            <div class="flex items-center gap-2 text-[11px] text-slate-400 italic">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Rekomendasi: 1200 x 800 px (Rasio 3:2).
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="p-6 bg-slate-50/50 rounded-2xl border border-slate-100">
                            <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-4">Badge Melayang 1 (Atas Kiri)</p>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400">Judul</label>
                                    <input type="text" name="landing_badge1_title" value="{{ $settings['landing_badge1_title'] ?? 'Data Aman' }}" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400">Subjudul</label>
                                    <input type="text" name="landing_badge1_subtitle" value="{{ $settings['landing_badge1_subtitle'] ?? 'Tersimpan di Cloud' }}" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm">
                                </div>
                            </div>
                        </div>
                        <div class="p-6 bg-slate-50/50 rounded-2xl border border-slate-100">
                            <p class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-4">Badge Melayang 2 (Bawah Kanan)</p>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400">Judul</label>
                                    <input type="text" name="landing_badge2_title" value="{{ $settings['landing_badge2_title'] ?? 'Akses Cepat' }}" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400">Subjudul</label>
                                    <input type="text" name="landing_badge2_subtitle" value="{{ $settings['landing_badge2_subtitle'] ?? '24/7 Tersedia' }}" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Tab: About --}}
    <div x-show="tab === 'about'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" x-cloak>
        <form action="{{ route('settings.pages.update') }}" method="POST">
            @csrf
            <input type="hidden" name="active_tab" value="about">
            
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800">Laman Tentang: Header & Fitur</h3>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition-all">Simpan Laman Tentang</button>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pb-8 border-b border-slate-50">
                        <div class="space-y-4">
                            <label class="text-sm font-bold text-slate-700">Penyusun Judul Header</label>
                            <div class="flex gap-4 items-center">
                                <div class="flex-1 space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Teks Normal</label>
                                    <input type="text" name="about_title_p1" value="{{ $settings['about_title_p1'] ?? 'Tentang' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm">
                                </div>
                                <div class="flex-1 space-y-1">
                                    <label class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Teks Sorotan (Biru)</label>
                                    <input type="text" name="about_title_span" value="{{ $settings['about_title_span'] ?? 'Buku Induk' }}" class="w-full bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-2.5 text-sm font-bold text-indigo-700">
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Subjudul Header</label>
                            <textarea name="landing_about_subtitle" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">{{ $settings['landing_about_subtitle'] ?? 'Sistem pencatatan dan pengelolaan data induk siswa modern yang dioptimalkan untuk kebutuhan SD Muhammadiyah Gisting.' }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @for($i = 1; $i <= 3; $i++)
                            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 space-y-4">
                                <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Fitur {{ $i }}</p>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase">Judul</label>
                                    <input type="text" name="landing_f{{ $i }}_title" value="{{ $settings["landing_f{$i}_title"] ?? ($i == 1 ? 'Aman & Terpusat' : ($i == 2 ? 'Akses Instan' : 'Antarmuka Intuitif')) }}" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm font-bold">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase">Deskripsi</label>
                                    <textarea name="landing_f{{ $i }}_desc" rows="4" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-xs leading-relaxed text-slate-500">{{ $settings["landing_f{$i}_desc"] ?? ($i == 1 ? 'Semua data identitas, akademik, dan riwayat siswa tersimpan dengan aman pada pangkalan data terpusat.' : ($i == 2 ? 'Pencarian, pembaruan, dan pelaporan informasi siswa bisa dilakukan kapan saja.' : 'Desain aplikasi yang modern, nyaman dipandang, dan mudah dipahami.')) }}</textarea>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Tab: Contact --}}
    <div x-show="tab === 'contact'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" x-cloak>
        <form action="{{ route('settings.pages.update') }}" method="POST">
            @csrf
            <input type="hidden" name="active_tab" value="contact">
            
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800">Laman Kontak: Info & Lokasi</h3>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition-all">Simpan Laman Kontak</button>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pb-8 border-b border-slate-50">
                        <div class="p-8 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-3xl space-y-6 shadow-lg shadow-indigo-100 text-white">
                             <div class="space-y-4">
                                <label class="text-xs font-bold text-indigo-100 uppercase tracking-widest block">Penyusun Judul</label>
                                <div class="flex gap-4">
                                    <div class="flex-1 space-y-1">
                                        <label class="text-[10px] font-bold text-indigo-200 uppercase tracking-widest">Teks Normal</label>
                                        <input type="text" name="contact_title_p1" value="{{ $settings['contact_title_p1'] ?? 'Hubungi' }}" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2.5 text-sm font-bold focus:bg-white/20 outline-none">
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <label class="text-[10px] font-bold text-indigo-200 uppercase tracking-widest">Teks Sorotan</label>
                                        <input type="text" name="contact_title_span" value="{{ $settings['contact_title_span'] ?? 'Kami' }}" class="w-full bg-white border-0 rounded-xl px-4 py-2.5 text-sm font-black text-indigo-700">
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-indigo-100 uppercase tracking-widest block">Subjudul Laman</label>
                                <textarea name="landing_contact_subtitle" rows="3" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-sm placeholder-indigo-200 focus:bg-white/20 outline-none">{{ $settings['landing_contact_subtitle'] ?? 'Ada kendala tentang aplikasi atau perihal administratif data siswa? Tim administrasi SD Muhammadiyah Gisting siap membantu.' }}</textarea>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Alamat / Lokasi</label>
                                <div class="flex gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <input type="text" name="landing_contact_address" value="{{ $settings['landing_contact_address'] ?? 'Jl. Raya Gisting Raya No. 1, Kabupaten Tanggamus, Lampung' }}" class="w-full bg-white border border-slate-100 rounded-xl px-4 py-2 text-sm text-slate-700">
                                </div>
                            </div>
                            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Nomor Telepon</label>
                                <div class="flex gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center text-amber-700 shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <input type="text" name="landing_contact_phone" value="{{ $settings['landing_contact_phone'] ?? '(0722) 123456' }}" class="w-full bg-white border border-slate-100 rounded-xl px-4 py-2 text-sm text-slate-700">
                                </div>
                            </div>
                            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Email Resmi</label>
                                <div class="flex gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-sky-100 flex items-center justify-center text-sky-700 shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <input type="text" name="landing_contact_email" value="{{ $settings['landing_contact_email'] ?? 'info@sdmuhammadiyahgisting.sch.id' }}" class="w-full bg-white border border-slate-100 rounded-xl px-4 py-2 text-sm text-slate-700">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
