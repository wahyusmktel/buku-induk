@extends('layouts.app')

@section('title', 'Detail Siswa: ' . $siswa->nama)
@section('header_title', 'Profil Detail Siswa')
@section('breadcrumb')
    <a href="{{ route('siswas.index') }}" class="hover:text-sky-600 transition-colors">Data Pokok Siswa</a>
    <span class="text-slate-300 mx-1">/</span>
    <span class="text-slate-800 font-semibold italic">{{ $siswa->nama }}</span>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-8 pb-20">
    
    <!-- Top Identity Card (Premium Banner) -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="h-48 bg-gradient-to-br from-sky-600 via-indigo-700 to-indigo-900 relative">
            <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\"20\" height=\"20\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w4.org/2000/svg\"%3E%3Cg fill=\"%23fff\" fill-opacity=\"1\" fill-rule=\"evenodd\"%3E%3Ccircle cx=\"3\" cy=\"3\" r=\"3\"/%3E%3Cpath d=\"M10 10c0-2.21 1.79-4 4-4s4 1.79 4 4-1.79 4-4 4-4-1.79-4-4z\"/%3E%3C/g%3E%3C/svg%3E');"></div>
            
            <div class="absolute -bottom-16 left-12 p-1.5 bg-white rounded-3xl shadow-2xl">
                <div class="w-32 h-32 bg-indigo-50 text-indigo-700 rounded-[1.25rem] flex items-center justify-center font-black text-4xl shadow-inner border-2 border-slate-50 uppercase">
                    {{ substr($siswa->nama, 0, 2) }}
                </div>
            </div>
        </div>

        <div class="pt-20 pb-10 px-12 flex flex-col md:flex-row justify-between items-start md:items-end gap-8">
            <div class="space-y-4">
                <div>
                    <h2 class="text-4xl font-black text-slate-800 tracking-tight leading-none">{{ $siswa->nama }}</h2>
                    @if($siswa->nama_panggilan)
                        <p class="text-indigo-500 font-bold mt-1 text-lg">"{{ $siswa->nama_panggilan }}"</p>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-black bg-sky-50 text-sky-700 border border-sky-100 uppercase tracking-wider">
                        NISN: {{ $siswa->nisn ?? '-' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-black bg-slate-50 text-slate-700 border border-slate-100 uppercase tracking-wider">
                        NIS: {{ $siswa->nis ?? '-' }}
                    </span>
                    @if($siswa->tingkat_kelas)
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-black bg-amber-50 text-amber-700 border border-amber-100 uppercase tracking-wider">
                        Tingkat: {{ $siswa->tingkat_kelas }}
                    </span>
                    @endif
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-black bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase tracking-wider">
                        Rombel: {{ $siswa->rombel ? $siswa->rombel->nama : ($siswa->rombel_saat_ini ?? '-') }}
                    </span>
                    @php
                        $statusBadge = match($siswa->status) {
                            'Aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                            'Lulus' => 'bg-sky-50 text-sky-700 border-sky-100',
                            'Keluar/Mutasi' => 'bg-rose-50 text-rose-700 border-rose-100',
                            default => 'bg-slate-50 text-slate-700 border-slate-100'
                        };
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-black {{ $statusBadge }} border uppercase tracking-wider">
                        {{ $siswa->status ?? 'Aktif' }}
                    </span>
                </div>
            </div>

            @hasanyrole('Super Admin|Operator|Tata Usaha')
            <div class="flex gap-4">
                @if($siswa->nisn)
                <a href="{{ route('buku-induk.show', $siswa->nisn) }}" class="px-6 py-3 bg-indigo-600 text-white text-sm font-black rounded-2xl hover:bg-indigo-700 transition-all hover:scale-105 active:scale-95 shadow-lg shadow-indigo-100 flex items-center gap-2">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Buku Induk
                </a>
                @else
                <button disabled title="Siswa tidak memiliki NISN" class="px-6 py-3 bg-slate-200 text-slate-500 text-sm font-black rounded-2xl shadow border border-slate-300 flex items-center gap-2 cursor-not-allowed">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Buku Induk
                </button>
                @endif

                {{-- Surat Keterangan dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                            class="px-6 py-3 bg-emerald-600 text-white text-sm font-black rounded-2xl hover:bg-emerald-700 transition-all hover:scale-105 active:scale-95 shadow-lg shadow-emerald-100 flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Surat Keterangan
                        <svg class="w-3.5 h-3.5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak x-transition
                         class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-30">
                        <a href="{{ route('cetak.surat-aktif', $siswa->id) }}?preview=1"
                           target="_blank"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Preview Surat Aktif
                        </a>
                        <a href="{{ route('cetak.surat-aktif', $siswa->id) }}"
                           target="_blank"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            PDF Surat Aktif
                        </a>
                        @if($siswa->status === 'Lulus' && $siswa->nisn)
                        <div class="border-t border-slate-100 my-1"></div>
                        <a href="{{ route('cetak.surat-lulus', $siswa->nisn) }}?preview=1"
                           target="_blank"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Preview Surat Lulus
                        </a>
                        <a href="{{ route('cetak.surat-lulus', $siswa->nisn) }}"
                           target="_blank"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            PDF Surat Lulus
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endhasanyrole
        </div>
    </div>

    <!-- MAIN DATA GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- SIDEBAR INFO (Periodik & Status) -->
        <div class="lg:col-span-4 space-y-8 order-2 lg:order-1">
            
            <!-- SECTION: DATA FISIK & PERIODIK -->
            <div class="bg-indigo-800 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-100 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                <h3 class="text-xs font-black uppercase tracking-[0.2em] mb-8 opacity-60 flex items-center gap-2">
                    <span class="w-2 h-0.5 bg-indigo-400"></span> Data Periodik & Fisik
                </h3>
                <div class="grid grid-cols-2 gap-y-10 gap-x-6">
                    <div>
                        <p class="text-[0.65rem] font-bold opacity-60 uppercase mb-2">Berat Badan</p>
                        <p class="text-3xl font-black">{{ $siswa->keadaanJasmani->berat_badan ?? '-' }}<span class="text-sm font-medium opacity-50 ml-1">kg</span></p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-bold opacity-60 uppercase mb-2">Tinggi Badan</p>
                        <p class="text-3xl font-black">{{ $siswa->keadaanJasmani->tinggi_badan ?? '-' }}<span class="text-sm font-medium opacity-50 ml-1">cm</span></p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-bold opacity-60 uppercase mb-2">Jarak Sekolah</p>
                        <p class="text-xl font-black">{{ $siswa->dataPeriodik->jarak_tempat_tinggal_ke_sekolah ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-bold opacity-50 uppercase mb-1">Golongan Darah</p>
                        <p class="text-indigo-100 font-black">{{ $siswa->keadaanJasmani->golongan_darah ?? '-' }}</p>
                    </div>
                </div>
                <div class="mt-10 pt-8 border-t border-white/10">
                    <p class="text-[0.65rem] font-bold opacity-50 uppercase mb-1">Jumlah Saudara</p>
                    <p class="text-indigo-100 font-black">{{ $siswa->dataPeriodik->jml_saudara_kandung ?? '0' }} <span class="text-[10px] font-medium opacity-60">Kandung</span> &bull; {{ $siswa->dataPeriodik->jml_saudara_tiri ?? '0' }} <span class="text-[10px] font-medium opacity-60">Tiri</span> &bull; {{ $siswa->dataPeriodik->jml_saudara_angkat ?? '0' }} <span class="text-[10px] font-medium opacity-60">Angkat</span></p>
                </div>
            </div>

            <!-- SECTION: KESEHATAN & KHUSUS -->
            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm space-y-6">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-rose-500 rounded-full"></span> Kesehatan
                </h3>
                <div class="space-y-4">
                    <div class="p-4 bg-rose-50/50 rounded-2xl border border-rose-100/50">
                        <p class="text-[10px] font-bold text-rose-400 uppercase mb-1">Riwayat Penyakit</p>
                        <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ $siswa->keadaanJasmani->nama_riwayat_penyakit ?? 'Tidak ada riwayat penyakit signifikan' }}</p>
                    </div>
                    <div class="p-4 bg-amber-50/50 rounded-2xl border border-amber-100/50">
                        <p class="text-[10px] font-bold text-amber-500 uppercase mb-1">Kelainan Jasmani / Kebutuhan Khusus</p>
                        <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ $siswa->keadaanJasmani->kelainan_jasmani ?? 'Tidak ada' }}</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- MAIN COLUMN (Details) -->
        <div class="lg:col-span-8 space-y-8 order-1 lg:order-2">
            
            <!-- SECTION 1: BIODATA & IDENTITAS -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-slate-50/50 px-10 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.15em] flex items-center gap-3">
                        <span class="w-2 h-2 bg-sky-500 rounded-full animate-pulse"></span>
                        Biodata & Identitas Murid
                    </h3>
                    <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                        <div class="space-y-1">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-1.5">No. NIK (KTP)</p>
                            <p class="text-base font-bold text-slate-700 font-mono">{{ $siswa->nik ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tempat, Tanggal Lahir</p>
                            <p class="text-base font-bold text-slate-700 capitalize">{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') : '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-1.5">Jenis Kelamin</p>
                            <p class="text-base font-bold text-slate-700">{{ $siswa->jenis_kelamin == 'L' || $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-1.5">Agama</p>
                            <p class="text-base font-bold text-slate-700">{{ $siswa->agama ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-1.5">Kewarganegaraan</p>
                            <p class="text-base font-bold text-slate-700">{{ $siswa->kewarganegaraan ?? 'WNI' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-1.5">Bahasa Sehari-hari</p>
                            <p class="text-base font-bold text-slate-700">{{ $siswa->dataPeriodik->bahasa_sehari_hari ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: ALAMAT & KONTAK -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-10">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.15em] mb-10 flex items-center gap-3">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                    Alamat & Kontak
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-1.5">Alamat Lengkap</p>
                            <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ $siswa->dataPeriodik->alamat_tinggal ?? '-' }}</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-2xl w-max">
                            <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Bertempat Tinggal Pada</p>
                            <p class="text-xs font-bold text-slate-600">{{ $siswa->dataPeriodik->bertempat_tinggal_pada ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-3">Kontak Siswa</p>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div>
                                    <p class="text-sm font-bold text-slate-700">{{ $siswa->nomor_telepon ?? $siswa->telepon ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: DATA ORANG TUA (AYAH & IBU) -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-slate-50/50 px-10 py-5 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.15em]">Data Orang Tua Kandung</h3>
                </div>
                
                @php
                    $ayah = $siswa->dataOrangTua ? $siswa->dataOrangTua->where('jenis', 'Ayah')->first() : null;
                    $ibu = $siswa->dataOrangTua ? $siswa->dataOrangTua->where('jenis', 'Ibu')->first() : null;
                    $wali = $siswa->dataOrangTua ? $siswa->dataOrangTua->where('jenis', 'Wali')->first() : null;
                @endphp

                <div class="p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <!-- Ayah -->
                        <div class="space-y-6 border-r border-slate-50 pr-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-black">A</div>
                                <h4 class="font-black text-slate-800 uppercase text-xs tracking-widest">Ayah Kandung</h4>
                            </div>
                            <div class="space-y-4">
                                <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Nama Ayah</p><p class="text-sm font-bold text-slate-800">{{ $ayah->nama ?? '-' }}</p></div>
                                <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Pendidikan Terakhir</p><p class="text-xs font-bold text-slate-700">{{ $ayah->pendidikan_terakhir ?? '-' }}</p></div>
                                <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Pekerjaan</p><p class="text-xs font-bold text-slate-700">{{ $ayah->pekerjaan ?? '-' }}</p></div>
                            </div>
                        </div>

                        <!-- Ibu -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center font-black">I</div>
                                <h4 class="font-black text-slate-800 uppercase text-xs tracking-widest">Ibu Kandung</h4>
                            </div>
                            <div class="space-y-4">
                                <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Nama Ibu</p><p class="text-sm font-bold text-slate-800">{{ $ibu->nama ?? '-' }}</p></div>
                                <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Pendidikan Terakhir</p><p class="text-xs font-bold text-slate-700">{{ $ibu->pendidikan_terakhir ?? '-' }}</p></div>
                                <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Pekerjaan</p><p class="text-xs font-bold text-slate-700">{{ $ibu->pekerjaan ?? '-' }}</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: DATA WALI -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-10">
                 <div class="flex items-center justify-between mb-8">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Data Wali Murid</h3>
                    <div class="px-3 py-1 bg-slate-50 text-[10px] font-bold text-slate-400 rounded-full border border-slate-100 uppercase tracking-tighter">Optional</div>
                 </div>
                 @if($wali)
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Nama Wali</p><p class="text-sm font-bold text-slate-800">{{ $wali->nama ?? '-' }}</p></div>
                    <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Status Hubungan</p><p class="text-xs font-bold text-slate-700">{{ $wali->status_hubungan_wali ?? '-' }}</p></div>
                    <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Pendidikan</p><p class="text-xs font-bold text-slate-700">{{ $wali->pendidikan_terakhir ?? '-' }}</p></div>
                    <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Pekerjaan</p><p class="text-xs font-bold text-slate-700">{{ $wali->pekerjaan ?? '-' }}</p></div>
                 </div>
                 @else
                 <div class="py-6 flex flex-col items-center justify-center text-center opacity-40">
                    <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.268 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-xs font-bold">Data wali tidak diisi (Tinggal dengan Orang Tua)</p>
                 </div>
                 @endif
            </div>

            <!-- SECTION 5: BEASISWA & REGISTRASI -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Beasiswa -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-10 flex flex-col">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-8">Riwayat Beasiswa</h3>
                    @if($siswa->beasiswa && $siswa->beasiswa->count() > 0)
                        <div class="space-y-4">
                            @foreach($siswa->beasiswa as $beasiswa)
                                <div class="flex flex-col gap-1 border-l-4 border-emerald-400 pl-4 py-1">
                                    <span class="text-[9px] font-black text-slate-400 uppercase">{{ $beasiswa->tahun ?? '-' }}</span>
                                    <span class="text-sm font-bold text-slate-700">{{ $beasiswa->jenis_beasiswa ?? '-' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="h-full flex flex-col items-center justify-center text-center opacity-30 py-4">
                             <p class="text-xs font-bold">Belum ada riwayat beasiswa.</p>
                        </div>
                    @endif
                </div>

                <!-- Registrasi Siswa -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-10 flex flex-col">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-8">Registrasi Siswa</h3>
                    @if($siswa->registrasi && $siswa->registrasi->count() > 0)
                        <div class="space-y-4">
                            @foreach($siswa->registrasi as $reg)
                                <div class="flex flex-col gap-1 border-l-4 border-indigo-400 pl-4 py-1">
                                    <span class="text-[9px] font-black text-slate-400 uppercase">{{ $reg->jenis_registrasi ?? '-' }}</span>
                                    <span class="text-sm font-bold text-slate-700">{{ $reg->keterangan ?? '-' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="h-full flex flex-col items-center justify-center text-center opacity-30 py-4">
                             <p class="text-xs font-bold">Belum ada catatan registrasi keluar/pindah/tamat.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
