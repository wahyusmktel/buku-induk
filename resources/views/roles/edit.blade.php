@extends('layouts.app')

@section('title', 'Perbarui Role Sistem')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    
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
                    <a href="{{ route('roles.index') }}" class="ml-1 md:ml-2 text-slate-400 hover:text-indigo-600 transition-colors uppercase tracking-wider text-[0.7rem] font-bold">Manajemen Role</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="ml-1 md:ml-2 text-slate-400 uppercase tracking-wider text-[0.7rem] font-bold">Edit Role</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Perbarui Hak Akses</h2>
            <p class="text-sm font-medium text-slate-500 mt-1.5 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                Modifikasi nama identitas untuk role <span class="text-indigo-600 font-bold underline decoration-indigo-200 underline-offset-4">{{ $role->name }}</span>
            </p>
        </div>
        <div class="bg-white px-4 py-2.5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-lg">
                {{ substr($role->name, 0, 1) }}
            </div>
            <div>
                <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">ID Role</p>
                <p class="text-sm font-bold text-slate-700 leading-none">#{{ $role->id }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Detail Informasi Role
            </h3>
        </div>

        <form action="{{ route('roles.update', $role) }}" method="POST" class="p-8 md:p-10">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Name -->
                <div class="space-y-2">
                    <label for="name" class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-wider ml-1">Nama Identitas Role</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required
                            class="w-full pl-11 pr-4 py-3.5 text-sm rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 shadow-inner @error('name') border-rose-300 ring-rose-300/20 @enderror"
                            placeholder="Contoh: Administrator Utama">
                    </div>
                    <p class="text-[0.65rem] text-slate-400 mt-2 px-1 italic">* Pastikan nama role unik dan mendeskripsikan tingkat aksesnya.</p>
                    @error('name')
                        <p class="mt-1.5 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-12 flex items-center justify-end gap-4 pt-10 border-t border-slate-100">
                <a href="{{ route('roles.index') }}" class="px-8 py-3.5 text-sm font-black text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-2xl transition-all uppercase tracking-widest">
                    Batal
                </a>
                <button type="submit" class="px-10 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-black rounded-2xl shadow-xl shadow-indigo-600/20 transition-all hover:translate-y-[-2px] hover:shadow-2xl active:translate-y-0 flex items-center gap-3 uppercase tracking-widest">
                    <svg class="w-5 h-5 translate-y-[-1px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
