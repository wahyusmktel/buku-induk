@extends('layouts.app')

@section('title', 'Detail Siswa: ' . $siswa->nama)
@section('header_title', 'Profil Detail Siswa')
@section('breadcrumb')
    <a href="{{ route('siswas.index') }}" class="hover:text-sky-600 transition-colors">Data Pokok Siswa</a>
    <span class="text-slate-300 mx-1">/</span>
    <span class="text-slate-800 font-semibold italic">{{ $siswa->nama }}</span>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    
    <!-- Top Identity Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="h-32 bg-gradient-to-r from-sky-600 to-indigo-700 relative">
            <div class="absolute -bottom-12 left-8 p-1 bg-white rounded-2xl shadow-xl">
                <div class="w-24 h-24 bg-sky-100 text-sky-700 rounded-xl flex items-center justify-center font-black text-2xl shadow-inner border-2 border-slate-50">
                    {{ substr($siswa->nama, 0, 2) }}
                </div>
            </div>
        </div>
        <div class="pt-16 pb-8 px-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">{{ $siswa->nama }}</h2>
                <div class="flex flex-wrap items-center gap-4 mt-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-100">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 014 0"/></svg>
                        NISN: {{ $siswa->nisn ?? '-' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Rombel: {{ $siswa->rombel_saat_ini ?? '-' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $siswa->jk == 'L' ? 'bg-blue-50 text-blue-700' : 'bg-rose-50 text-rose-700' }} border {{ $siswa->jk == 'L' ? 'border-blue-100' : 'border-rose-100' }}">
                        {{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </span>
                </div>
            </div>
            
            @hasanyrole('Super Admin|Operator|Tata Usaha')
            <div class="flex gap-3">
                <a href="{{ route('siswas.edit', $siswa) }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Ubah Profile
                </a>
            </div>
            @endhasanyrole
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Primary Info -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Section: Identitas & Alamat -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-sky-600 rounded-full"></span>
                    Informasi Pribadi & Alamat
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tempat, Tanggal Lahir</p>
                        <p class="font-bold text-slate-700">{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') : '-' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">NIK (No. KTP)</p>
                        <p class="font-bold text-slate-700 font-mono">{{ $siswa->nik ?? '-' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Agama</p>
                        <p class="font-bold text-slate-700">{{ $siswa->agama ?? '-' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Hubungan Kontak</p>
                        <p class="font-bold text-slate-700">{{ $siswa->hp ?? $siswa->telepon ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2 space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Alamat Domisili</p>
                        <p class="font-bold text-slate-700">{{ $siswa->alamat ?? '-' }} (RT {{ $siswa->rt }}/RW {{ $siswa->rw }}), {{ $siswa->dusun }}, {{ $siswa->kelurahan }}, {{ $siswa->kecamatan }}, {{ $siswa->kode_pos }}</p>
                    </div>
                </div>
            </div>

            <!-- Section: Data Orang Tua -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-slate-50/50 px-8 py-4 border-b border-slate-100 flex items-center gap-2">
                     <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                     <h3 class="text-sm font-black text-slate-600 uppercase tracking-widest">Data Orang Tua / Wali</h3>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-12">
                     <!-- Ayah -->
                     <div class="space-y-4">
                        <h4 class="font-black text-slate-800 border-b border-slate-100 pb-2">Data Ayah</h4>
                        <div class="space-y-3">
                            <div><p class="text-[0.65rem] font-bold text-slate-400 uppercase">Nama Ayah</p><p class="text-sm font-bold text-slate-700">{{ $siswa->nama_ayah ?? '-' }}</p></div>
                            <div><p class="text-[0.65rem] font-bold text-slate-400 uppercase">Pekerjaan</p><p class="text-sm font-bold text-slate-700">{{ $siswa->pekerjaan_ayah ?? '-' }}</p></div>
                            <div><p class="text-[0.65rem] font-bold text-slate-400 uppercase">NIK Ayah</p><p class="text-sm font-bold text-slate-700 font-mono">{{ $siswa->nik_ayah ?? '-' }}</p></div>
                        </div>
                     </div>
                     <!-- Ibu -->
                     <div class="space-y-4">
                        <h4 class="font-black text-slate-800 border-b border-slate-100 pb-2">Data Ibu</h4>
                        <div class="space-y-3">
                            <div><p class="text-[0.65rem] font-bold text-slate-400 uppercase">Nama Ibu</p><p class="text-sm font-bold text-slate-700">{{ $siswa->nama_ibu ?? '-' }}</p></div>
                            <div><p class="text-[0.65rem] font-bold text-slate-400 uppercase">Pekerjaan</p><p class="text-sm font-bold text-slate-700">{{ $siswa->pekerjaan_ibu ?? '-' }}</p></div>
                            <div><p class="text-[0.65rem] font-bold text-slate-400 uppercase">NIK Ibu</p><p class="text-sm font-bold text-slate-700 font-mono">{{ $siswa->nik_ibu ?? '-' }}</p></div>
                        </div>
                     </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Secondary Info -->
        <div class="space-y-8">
            
            <!-- Periodik Data -->
            <div class="bg-indigo-600 rounded-3xl shadow-lg shadow-indigo-100 p-8 text-white">
                <h3 class="text-sm font-black uppercase tracking-widest mb-6 opacity-80">Data Periodik</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[0.65rem] font-bold opacity-70 mb-1">Berat Badan</p>
                        <p class="text-2xl font-black">{{ $siswa->berat_badan ?? '-' }} <span class="text-sm font-medium opacity-60">kg</span></p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-bold opacity-70 mb-1">Tinggi Badan</p>
                        <p class="text-2xl font-black">{{ $siswa->tinggi_badan ?? '-' }} <span class="text-sm font-medium opacity-60">cm</span></p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-bold opacity-70 mb-1">Lingkar Kepala</p>
                        <p class="text-xl font-black">{{ $siswa->lingkar_kepala ?? '-' }} <span class="text-xs font-medium opacity-60">cm</span></p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-bold opacity-70 mb-1">Jarak Sekolah</p>
                        <p class="text-xl font-black">{{ $siswa->jarak_rumah_ke_sekolah_km ?? '-' }} <span class="text-xs font-medium opacity-60">KM</span></p>
                    </div>
                </div>
            </div>

            <!-- Dokumentasi -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6">Dokumentasi & Bantuan</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-400">Penerima KIP</span>
                        <span class="text-sm font-black text-slate-700">{{ $siswa->penerima_kip ?? 'Tidak' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-400">Layak PIP</span>
                        <span class="text-sm font-black {{ $siswa->layak_pip == 'Ya' ? 'text-emerald-600' : 'text-slate-700' }}">{{ $siswa->layak_pip ?? 'Tidak' }}</span>
                    </div>
                    <div class="flex flex-col py-2 border-b border-slate-50 gap-1">
                        <span class="text-sm font-bold text-slate-400">Nomor Registrasi Akta</span>
                        <span class="text-sm font-black text-slate-700 font-mono">{{ $siswa->no_registrasi_akta_lahir ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col py-2 gap-1">
                        <span class="text-sm font-bold text-slate-400">Sekolah Asal</span>
                        <span class="text-sm font-black text-slate-700">{{ $siswa->sekolah_asal ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
