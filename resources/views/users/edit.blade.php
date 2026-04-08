@extends('layouts.app')

@section('title', 'Perbarui Pengguna Sistem')

@section('content')
<div x-data="{ 
    password: '', 
    confirmation: '', 
    showPassword: false, 
    showConfirm: false,
    get strength() {
        if (!this.password) return { label: '', color: 'bg-slate-200', text: 'text-slate-400', width: '0%' };
        let s = 0;
        if (this.password.length > 6) s++;
        if (this.password.length > 10) s++;
        if (/[A-Z]/.test(this.password)) s++;
        if (/[0-9]/.test(this.password)) s++;
        if (/[^A-Za-z0-9]/.test(this.password)) s++;
        
        if (s <= 2) return { label: 'Lemah', color: 'bg-rose-500', text: 'text-rose-500', width: '33%' };
        if (s <= 4) return { label: 'Kuat', color: 'bg-amber-500', text: 'text-amber-500', width: '66%' };
        return { label: 'Sangat Kuat', color: 'bg-emerald-500', text: 'text-emerald-500', width: '100%' };
    }
}" class="max-w-4xl mx-auto px-4 py-8">
    
    {{-- Breadcrumb --}}
    <nav class="flex text-sm font-medium text-slate-500 mb-6" aria-label="Breadcrumb">
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
                    <a href="{{ route('users.index') }}" class="ml-1 md:ml-2 text-slate-400 hover:text-indigo-600 transition-colors uppercase tracking-wider text-[0.7rem] font-bold">Manajemen Pengguna</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="ml-1 md:ml-2 text-slate-400 uppercase tracking-wider text-[0.7rem] font-bold">Edit Pengguna</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Perbarui Pengguna</h2>
            <p class="text-sm font-medium text-slate-500 mt-1.5 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                Ubah informasi profil dan hak akses untuk <span class="text-indigo-600 font-bold underline decoration-indigo-200 underline-offset-4">{{ $user->name }}</span>
            </p>
        </div>
        <div class="bg-white px-4 py-2.5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Terdaftar Sejak</p>
                <p class="text-sm font-bold text-slate-700 leading-none">{{ $user->created_at->translatedFormat('d F Y') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden">
        <form action="{{ route('users.update', $user) }}" method="POST" class="p-8 md:p-10">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                {{-- Info Section --}}
                <div class="lg:col-span-12 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Name -->
                        <div class="space-y-2">
                            <label for="name" class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-wider ml-1">Nama Lengkap</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full pl-11 pr-4 py-3.5 text-sm rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 shadow-inner @error('name') border-rose-300 ring-rose-300/20 @enderror"
                                    placeholder="Masukkan nama lengkap">
                            </div>
                            @error('name')
                                <p class="mt-1.5 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-wider ml-1">Alamat Email</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full pl-11 pr-4 py-3.5 text-sm rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 shadow-inner @error('email') border-rose-300 ring-rose-300/20 @enderror"
                                    placeholder="nama@domain.com">
                            </div>
                            @error('email')
                                <p class="mt-1.5 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Security Alert --}}
                    <div class="p-5 bg-indigo-50/50 rounded-2xl border border-indigo-100 flex gap-4 animate-pulse-slow">
                        <div class="shrink-0 w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-indigo-900">Keamanan Akun</h4>
                            <p class="text-xs text-indigo-700/80 mt-1 leading-relaxed">Kosongkan kolom kata sandi jika Anda tidak ingin mengubahnya. Jika diisi, pastikan menggunakan kombinasi karakter yang kuat.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                        <!-- Password -->
                        <div class="space-y-2">
                            <label for="password" class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-wider ml-1">Kata Sandi Baru (Opsional)</label>
                            <div class="relative group">
                                <input :type="showPassword ? 'text' : 'password'" 
                                    name="password" id="password" x-model="password"
                                    class="w-full px-4 py-3.5 text-sm rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 shadow-inner @error('password') border-rose-300 ring-rose-300/20 @enderror"
                                    placeholder="••••••••">
                                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-indigo-600 transition-colors">
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L14.473 5.29M9.88 9.88L5.29 14.473M21 12a9.97 9.97 0 01-1.557 3.007L17.143 12.3A3.007 3.007 0 0117.143 12a3 3 0 01-3-3l-2.707-2.707M12 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.563 3.029l-1.372-1.372a3.007 3.007 0 01-.165-1.657"/></svg>
                                </button>
                            </div>
                            
                            {{-- Strength Meter --}}
                            <div x-show="password.length > 0" class="mt-3 space-y-2 animate-fade-in-down" style="display: none;">
                                <div class="flex items-center justify-between text-[0.65rem] font-black uppercase tracking-widest">
                                    <span class="text-slate-400">Kekuatan:</span>
                                    <span :class="strength.text" x-text="strength.label"></span>
                                </div>
                                <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full transition-all duration-500" :class="strength.color" :style="{ width: strength.width }"></div>
                                </div>
                            </div>
                            @error('password')
                                <p class="mt-1.5 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-wider ml-1">Ulangi Kata Sandi</label>
                            <div class="relative group">
                                <input :type="showConfirm ? 'text' : 'password'" 
                                    name="password_confirmation" id="password_confirmation" x-model="confirmation"
                                    class="w-full px-4 py-3.5 text-sm rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 shadow-inner"
                                    placeholder="••••••••">
                                <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-indigo-600 transition-colors">
                                    <svg x-show="!showConfirm" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showConfirm" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L14.473 5.29M9.88 9.88L5.29 14.473M21 12a9.97 9.97 0 01-1.557 3.007L17.143 12.3A3.007 3.007 0 0117.143 12a3 3 0 01-3-3l-2.707-2.707M12 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.563 3.029l-1.372-1.372a3.007 3.007 0 01-.165-1.657"/></svg>
                                </button>
                            </div>
                            
                            {{-- Matching Status --}}
                            <div x-show="confirmation.length > 0" class="mt-2 text-[0.7rem] font-bold animate-fade-in" style="display: none;">
                                <template x-if="password === confirmation">
                                    <span class="text-emerald-500 flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        Sandi sudah sesuai
                                    </span>
                                </template>
                                <template x-if="password !== confirmation">
                                    <span class="text-rose-500 flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Belum sesuai
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Roles Section --}}
                    <div class="pt-8 border-t border-slate-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-indigo-500 rounded-full"></div>
                            <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Penetapan Hak Akses (Role)</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($roles as $role)
                                <label class="relative flex items-center gap-3.5 p-4 border-2 border-slate-100 rounded-2xl cursor-pointer hover:border-indigo-200 hover:bg-indigo-50/30 transition-all group has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-4 has-[:checked]:ring-indigo-500/5">
                                    <div class="relative flex items-center justify-center">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                            class="peer w-5 h-5 text-indigo-600 border-slate-300 rounded-lg focus:ring-indigo-500 transition-all cursor-pointer"
                                            @if(is_array(old('roles', $userRoles)) && in_array($role->name, old('roles', $userRoles))) checked @endif>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-900 transition-colors">
                                            {{ $role->name }}
                                        </span>
                                        <span class="text-[0.6rem] font-bold text-slate-400 uppercase tracking-tighter peer-checked:text-indigo-500">
                                            Status: {{ in_array($role->name, $userRoles) ? 'Aktif' : 'Tersedia' }}
                                        </span>
                                    </div>
                                    <div class="absolute top-2 right-2 opacity-0 group-has-[:checked]:opacity-100 transition-opacity">
                                        <div class="bg-indigo-500 text-white rounded-full p-0.5">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('roles')
                            <p class="mt-3 text-xs font-bold text-rose-500 ml-1 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-12 flex items-center justify-end gap-4 pt-10 border-t border-slate-100">
                <a href="{{ route('users.index') }}" class="px-8 py-3.5 text-sm font-black text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-2xl transition-all uppercase tracking-widest">
                    Batal
                </a>
                <button type="submit" class="px-10 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-black rounded-2xl shadow-xl shadow-indigo-600/20 transition-all hover:translate-y-[-2px] hover:shadow-2xl active:translate-y-0 flex items-center gap-3 uppercase tracking-widest">
                    <svg class="w-5 h-5 translate-y-[-1px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fade-in-down {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .animate-fade-in-down { animation: fade-in-down 0.4s ease-out; }
    .animate-fade-in { animation: fade-in 0.3s ease-in; }
    .animate-pulse-slow {
        animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
</style>
@endsection
