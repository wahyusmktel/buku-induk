@extends('layouts.app')

@section('title', 'Tambah Role Sistem')
@section('header_title', 'Buat Role Baru')
@section('breadcrumb')
    <a href="{{ route('roles.index') }}" class="hover:text-sky-600 transition-colors">Manajemen Role</a>
    <span class="text-slate-300 mx-1">/</span>
    <span class="text-slate-800 font-semibold">Buat Baru</span>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Tambah Role Baru</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Masukkan nama role baru untuk dikelola ke dalam sistem.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <form action="{{ route('roles.store') }}" method="POST" class="p-6 md:p-8">
            @csrf

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Role</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring focus:ring-sky-500/20 transition-all font-medium text-slate-800 @error('name') border-rose-300 ring-rose-300/20 @enderror"
                        placeholder="Contoh: Admin Keuangan">
                    @error('name')
                        <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('roles.index') }}" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold rounded-xl shadow-sm shadow-sky-600/20 transition-all hover:shadow-md cursor-pointer flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Role
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
