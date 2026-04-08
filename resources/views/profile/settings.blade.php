@extends('layouts.app')

@section('title', 'Keamanan Akun')

@section('content')
<div class="px-4 py-6 sm:px-0">
    {{-- Breadcrumb --}}
    <nav class="flex mb-6 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="ml-1 md:ml-2 text-slate-400 font-bold uppercase tracking-wider text-[0.7rem]">Keamanan Akun</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pengaturan Keamanan</h1>
            <p class="text-slate-500 text-base mt-2">Perbarui kata sandi Anda secara berkala untuk menjaga keamanan akses sistem Buku Induk.</p>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-start gap-3 shadow-sm animate-fade-in-down">
                <svg class="w-5 h-5 text-emerald-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <h3 class="text-sm font-bold text-emerald-800">Berhasil!</h3>
                    <p class="text-sm text-emerald-600 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-slate-700 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Form Ganti Kata Sandi
                </h3>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" class="p-8 space-y-6" x-data="{ 
                password: '',
                password_confirmation: '',
                showCurrent: false,
                showNew: false,
                showConfirm: false,
                strength() {
                    let score = 0;
                    if (this.password.length >= 8) score++;
                    if (this.password.length >= 12) score++;
                    if (/[A-Z]/.test(this.password) && /[a-z]/.test(this.password)) score++;
                    if (/[0-9]/.test(this.password)) score++;
                    if (/[^A-Za-z0-9]/.test(this.password)) score++;
                    return score;
                },
                get strengthLabel() {
                    let score = this.strength();
                    if (score <= 2) return 'Kurang Kuat';
                    if (score === 3) return 'Kuat';
                    return 'Sangat Kuat';
                },
                get strengthClass() {
                    let score = this.strength();
                    if (score <= 2) return 'bg-rose-500';
                    if (score === 3) return 'bg-amber-500';
                    return 'bg-emerald-500';
                },
                get textColor() {
                    let score = this.strength();
                    if (score <= 2) return 'text-rose-600';
                    if (score === 3) return 'text-amber-600';
                    return 'text-emerald-600';
                },
                get matches() {
                    return this.password === this.password_confirmation && this.password_confirmation.length > 0;
                }
            }">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-wider mb-2">Kata Sandi Saat Ini</label>
                    <div class="relative group">
                        <input :type="showCurrent ? 'text' : 'password'" name="current_password" id="current_password" required
                            class="w-full px-4 py-3 text-sm rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 shadow-inner @error('current_password') border-rose-300 ring-rose-300/20 @enderror"
                            placeholder="••••••••">
                        <button type="button" @click="showCurrent = !showCurrent" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition-colors">
                            <svg x-show="!showCurrent" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showCurrent" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="mt-1.5 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-2">
                    <div class="relative">
                        <label for="password" class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-wider mb-2">Kata Sandi Baru</label>
                        <div class="relative group">
                            <input :type="showNew ? 'text' : 'password'" name="password" id="password" required x-model="password"
                                class="w-full px-4 py-3 text-sm rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 shadow-inner @error('password') border-rose-300 ring-rose-300/20 @enderror"
                                placeholder="Min. 8 karakter">
                            <button type="button" @click="showNew = !showNew" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition-colors">
                                <svg x-show="!showNew" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showNew" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                        @enderror

                        {{-- Strength Bar --}}
                        <div class="mt-3 px-1" x-show="password.length > 0">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[0.6rem] font-bold uppercase tracking-widest" :class="textColor">Kekuatan: <span x-text="strengthLabel"></span></span>
                                <span class="text-[0.6rem] font-bold opacity-50" x-text="(strength() * 20) + '%'"></span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full transition-all duration-500 ease-out" 
                                     :class="strengthClass"
                                     :style="'width: ' + (strength() * 20) + '%'">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-wider mb-2">Konfirmasi Sandi Baru</label>
                        <div class="relative group">
                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required x-model="password_confirmation"
                                class="w-full px-4 py-3 text-sm rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 shadow-inner"
                                placeholder="Ulangi sandi baru">
                            <button type="button" @click="showConfirm = !showConfirm" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition-colors">
                                <svg x-show="!showConfirm" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showConfirm" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        
                        {{-- Match Indicator --}}
                        <div class="mt-3 px-1" x-show="password_confirmation.length > 0">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded-full flex items-center justify-center shadow-sm" :class="matches ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'">
                                    <svg x-show="matches" class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <svg x-show="!matches" class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                                <span class="text-[0.65rem] font-bold uppercase tracking-wider" :class="matches ? 'text-emerald-600' : 'text-rose-600'" x-text="matches ? 'Sesuai' : 'Belum sesuai'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Captcha Section --}}
                <div class="bg-indigo-50/50 rounded-2xl p-6 border border-indigo-100 mt-4">
                    <label for="captcha" class="block text-[0.7rem] font-bold text-indigo-600 uppercase tracking-wider mb-3">Verifikasi Keamanan (Captcha)</label>
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="relative group">
                            <img src="{{ route('captcha') }}" id="captcha-img" alt="Captcha" class="rounded-xl border border-indigo-200 shadow-sm bg-white hover:brightness-95 transition-all">
                            <button type="button" onclick="document.getElementById('captcha-img').src='{{ route('captcha') }}?'+Math.random()" 
                                class="absolute -top-2 -right-2 p-1.5 bg-white text-indigo-600 rounded-full shadow-md border border-indigo-100 hover:bg-indigo-600 hover:text-white transition-all group-hover:scale-110"
                                title="Refresh Code">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            </button>
                        </div>
                        <div class="flex-1 w-full">
                            <input type="text" name="captcha" id="captcha" required
                                class="w-full px-4 py-3 text-sm rounded-xl border-indigo-200 bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold tracking-widest text-center shadow-inner @error('captcha') border-rose-300 ring-rose-300/20 @enderror"
                                placeholder="Masukkan kode di atas">
                            @error('captcha')
                                <p class="mt-1.5 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                    <button type="submit" class="w-full sm:w-auto px-10 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-extrabold rounded-2xl shadow-xl shadow-indigo-600/20 transition-all hover:shadow-2xl hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>
        
        <div class="mt-6 p-6 bg-slate-50 border border-dashed border-slate-200 rounded-3xl flex items-start gap-4 text-slate-500">
            <svg class="w-10 h-10 text-slate-300 shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="text-sm">
                <p class="font-bold text-slate-600 mb-1">Tips Keamanan:</p>
                <p>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol untuk kekuatan sandi maksimal. Hindari menggunakan kata sandi yang sama dengan layanan lain.</p>
            </div>
        </div>
    </div>
</div>
@endsection
