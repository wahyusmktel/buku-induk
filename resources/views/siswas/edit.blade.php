@extends('layouts.app')

@section('title', 'Ubah Data Siswa: ' . $siswa->nama)
@section('header_title', 'Ubah Profil Siswa')
@section('breadcrumb')
    <a href="{{ route('siswas.index') }}" class="hover:text-sky-600 transition-colors">Data Pokok Siswa</a>
    <span class="text-slate-300 mx-1">/</span>
    <span class="text-slate-800 font-semibold italic">Edit Profil</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-8 py-10 text-white">
            <h2 class="text-2xl font-black tracking-tight">Perbarui Data Siswa</h2>
            <p class="text-amber-100 text-sm mt-1 font-medium italic opacity-90">Sesuaikan informasi profil secara manual di bawah ini.</p>
        </div>

        <form action="{{ route('siswas.update', $siswa) }}" method="POST" class="p-8 space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Nama -->
                <div class="space-y-2">
                    <label for="nama" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $siswa->nama) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all font-bold text-slate-700">
                </div>

                <!-- NISN -->
                <div class="space-y-2">
                    <label for="nisn" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">NISN</label>
                    <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $siswa->nisn) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all font-mono font-bold text-slate-700">
                </div>

                <!-- NIK -->
                <div class="space-y-2">
                    <label for="nik" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">NIK (No. KTP)</label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik', $siswa->nik) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all font-mono font-bold text-slate-700">
                </div>

                <!-- Rombel -->
                <div class="space-y-2">
                    <label for="rombel" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Rombel Saat Ini</label>
                    <input type="text" name="rombel_saat_ini" id="rombel" value="{{ old('rombel_saat_ini', $siswa->rombel_saat_ini) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all font-bold text-slate-700">
                </div>
            </div>

            <div class="pt-6 border-t border-slate-50 flex gap-3">
                <a href="{{ route('siswas.show', $siswa) }}" class="flex-1 py-3 text-center text-sm font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-2xl transition-all">
                    Batal
                </a>
                <button type="submit" class="flex-[2] py-3 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-2xl shadow-lg transition-all hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
