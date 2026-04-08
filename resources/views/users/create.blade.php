@extends('layouts.app')

@section('title', 'Tambah Pengguna Sistem')
@section('header_title', 'Tambah Pengguna Baru')
@section('breadcrumb')
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
                    <span class="ml-1 md:ml-2 text-slate-400 uppercase tracking-wider text-[0.7rem] font-bold">Tambah Baru</span>
                </div>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Tambah Pengguna Baru</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Lengkapi form berikut untuk mendaftarkan pengguna ke dalam sistem.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <form action="{{ route('users.store') }}" method="POST" class="p-6 md:p-8">
            @csrf

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 shadow-inner @error('name') border-rose-300 ring-rose-300/20 @enderror"
                        placeholder="Contoh: Budi Santoso">
                    @error('name')
                        <p class="mt-1.5 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email Akses</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 shadow-inner @error('email') border-rose-300 ring-rose-300/20 @enderror"
                        placeholder="admin@sekolah.sch.id">
                    @error('email')
                        <p class="mt-1.5 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-wider mb-2">Kata Sandi Baru</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 shadow-inner @error('password') border-rose-300 ring-rose-300/20 @enderror"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="mt-1.5 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-wider mb-2">Ulangi Kata Sandi</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 shadow-inner"
                            placeholder="Sesuaikan dengan sandi">
                    </div>
                </div>

                <!-- Roles -->
                <div class="border-t border-slate-100 pt-6 mt-6">
                    <label class="block text-sm font-bold text-slate-700 mb-4">Pilih Hak Akses (Role)</label>
                    
                    @if($roles->isEmpty())
                        <div class="p-4 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl text-sm font-medium flex gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div>Saat ini belum ada data role. Harap buat role terlebih dahulu.</div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($roles as $role)
                                <label class="relative flex items-center gap-3 p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-indigo-50/50 hover:border-indigo-200 transition-all group has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50/50 has-[:checked]:shadow-sm">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                        class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 focus:ring-2 transition-all cursor-pointer"
                                        @if(is_array(old('roles')) && in_array($role->name, old('roles'))) checked @endif>
                                    <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-800 transition-colors block">
                                        {{ $role->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('roles')
                            <p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-10 flex items-center justify-end gap-3 pt-8 border-t border-slate-100">
                <a href="{{ route('users.index') }}" class="px-6 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all">
                    Batal
                </a>
                <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition-all hover:shadow-xl cursor-pointer flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
