@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="px-4 py-6 sm:px-0" x-data="{ 
    toasts: [],
    addToast(type, message) {
        const id = Date.now();
        this.toasts.push({ id, type, message, show: true });
        setTimeout(() => {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }, 3000);
    },
    photoPreview: null,
    isUploading: false,
    previewPhoto(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Manual Validation for size
        if (file.size > 1024 * 1024) {
            this.addToast('error', 'Ukuran gambar maksimal 1MB');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            this.photoPreview = e.target.result;
        };
        reader.readAsDataURL(file);

        // Auto Upload
        this.uploadPhoto(file);
    },
    async uploadPhoto(file) {
        this.isUploading = true;
        
        const formData = new FormData();
        formData.append('avatar', file);
        formData.append('name', {!! Js::from($user->name) !!});
        formData.append('email', {!! Js::from($user->email) !!});
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');

        try {
            const response = await fetch('{{ route('profile.update') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                this.addToast('success', result.message);
                // Update header avatar too
                const headerAvatar = document.getElementById('header-user-avatar');
                if (headerAvatar) headerAvatar.src = result.avatar_url;
            } else {
                throw new Error(result.message || 'Gagal mengunggah gambar');
            }
        } catch (error) {
            this.addToast('error', error.message);
            this.photoPreview = null; // Revert preview on failure
        } finally {
            this.isUploading = false;
        }
    }
}">
    {{-- Toast Container --}}
    <div class="fixed top-5 right-5 z-[9999] flex flex-col gap-3">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full opacity-0"
                 x-transition:enter-end="translate-x-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0 opacity-100"
                 x-transition:leave-end="translate-x-full opacity-0"
                 class="p-4 rounded-2xl shadow-2xl border flex items-center gap-3 min-w-[320px] backdrop-blur-md"
                 :class="toast.type === 'success' ? 'bg-emerald-50/90 border-emerald-200 text-emerald-800' : 'bg-rose-50/90 border-rose-200 text-rose-800'">
                
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                     :class="toast.type === 'success' ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white'">
                    <svg x-show="toast.type === 'success'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <svg x-show="toast.type === 'error'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-black uppercase tracking-widest opacity-60 mb-0.5" x-text="toast.type === 'success' ? 'Berhasil' : 'Kesalahan'"></p>
                    <p class="text-sm font-bold leading-tight" x-text="toast.message"></p>
                </div>
                <button @click="toast.show = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
    </div>

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
                    <span class="ml-1 md:ml-2 text-slate-400 font-bold uppercase tracking-wider text-[0.7rem]">Profil Saya</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="max-w-4xl mx-auto">
        <div class="flex flex-col md:flex-row items-start gap-8">
            {{-- Left Side: Profile Card --}}
            <div class="w-full md:w-1/3">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden text-center p-8 relative" :class="isUploading ? 'opacity-50' : ''">
                    <div class="relative inline-block group mb-4">
                        {{-- Loading Overlay --}}
                        <div x-show="isUploading" class="absolute inset-0 z-10 flex items-center justify-center bg-white/60 rounded-3xl backdrop-blur-[2px]">
                            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>

                        {{-- Avatar Circle --}}
                        <div class="w-32 h-32 rounded-3xl overflow-hidden shadow-lg shadow-indigo-100 bg-slate-100 border-4 border-white flex items-center justify-center">
                            <template x-if="!photoPreview">
                                <img src="{{ $user->avatar_url }}" alt="Profile Avatar" class="w-full h-full object-cover text-transparent">
                            </template>
                            <template x-if="photoPreview">
                                <img :src="photoPreview" alt="Preview Photo" class="w-full h-full object-cover">
                            </template>
                        </div>
                        
                        {{-- Upload Button (Floating) --}}
                        <button type="button" 
                                onclick="document.getElementById('avatar-input').click()"
                                class="absolute -bottom-2 -right-2 p-3 bg-white text-indigo-600 rounded-2xl shadow-xl border border-indigo-50 hover:bg-indigo-600 hover:text-white transition-all transform hover:scale-110 active:scale-95 group-hover:rotate-6">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </button>
                    </div>

                    <h3 class="text-xl font-bold text-slate-800">{{ $user->name }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ $user->email }}</p>
                    
                    <div class="mt-6 flex flex-wrap justify-center gap-2">
                        @foreach($user->getRoleNames() as $role)
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-[0.65rem] font-bold uppercase tracking-wider border border-indigo-100 shadow-sm">
                                {{ $role }}
                            </span>
                        @endforeach
                    </div>

                    <div class="mt-8 pt-8 border-t border-slate-100 space-y-3 text-left">
                        <div class="flex items-center gap-3 text-slate-500">
                            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-xs">Terdaftar: {{ $user->created_at->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side: Edit Form --}}
            <div class="flex-1 w-full">
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
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Sunting Informasi Profil
                        </h3>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Hidden File Input --}}
                        <input type="file" id="avatar-input" class="hidden" accept="image/*" @change="previewPhoto($event)">

                        <div>
                            <label for="name" class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-3 text-sm rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 shadow-inner @error('name') border-rose-300 ring-rose-300/20 @enderror">
                            @error('name')
                                <p class="mt-1.5 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-3 text-sm rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 shadow-inner @error('email') border-rose-300 ring-rose-300/20 @enderror">
                            <p class="text-[0.65rem] text-slate-400 mt-2 px-1 italic">* Email ini digunakan sebagai kredensial login utama Anda.</p>
                            @error('email')
                                <p class="mt-1.5 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                            <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-xl shadow-indigo-600/20 transition-all hover:shadow-2xl flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
