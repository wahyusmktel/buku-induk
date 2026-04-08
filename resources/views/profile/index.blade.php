@extends('layouts.app')

@section('title', 'Profil Pengguna')

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
                    <span class="ml-1 md:ml-2 text-slate-400 font-bold uppercase tracking-wider text-[0.7rem]">Profil Saya</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="max-w-4xl mx-auto">
        <div class="flex flex-col md:flex-row items-start gap-8">
            {{-- Left Side: Profile Card --}}
            <div class="w-full md:w-1/3">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden text-center p-8">
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-3xl font-extrabold shadow-lg shadow-indigo-200 mx-auto mb-4">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
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

                    <form action="{{ route('profile.update') }}" method="POST" class="p-8 space-y-6">
                        @csrf
                        @method('PUT')

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
