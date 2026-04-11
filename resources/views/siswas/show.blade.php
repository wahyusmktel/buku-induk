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
                    @if($siswa->nipd)
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-black bg-slate-50 text-slate-700 border border-slate-100 uppercase tracking-wider">
                        NIPD: {{ $siswa->nipd }}
                    </span>
                    @endif
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
                        <p class="text-3xl font-black">{{ $siswa->keadaanJasmani->berat_badan ?? $siswa->berat_badan ?? '-' }}<span class="text-sm font-medium opacity-50 ml-1">kg</span></p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-bold opacity-60 uppercase mb-2">Tinggi Badan</p>
                        <p class="text-3xl font-black">{{ $siswa->keadaanJasmani->tinggi_badan ?? $siswa->tinggi_badan ?? '-' }}<span class="text-sm font-medium opacity-50 ml-1">cm</span></p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-bold opacity-60 uppercase mb-2">Lingkar Kepala</p>
                        <p class="text-xl font-black">{{ $siswa->lingkar_kepala ?? '-' }}<span class="text-xs font-medium opacity-50 ml-1">cm</span></p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-bold opacity-60 uppercase mb-2">Jarak Sekolah</p>
                        <p class="text-xl font-black">{{ $siswa->dataPeriodik->jarak_tempat_tinggal_ke_sekolah ?? $siswa->jarak_rumah_ke_sekolah_km ?? '-' }}</p>
                    </div>
                </div>
                <div class="mt-10 pt-8 border-t border-white/10 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[0.65rem] font-bold opacity-50 uppercase mb-1">Golongan Darah</p>
                        <p class="text-indigo-100 font-black">{{ $siswa->keadaanJasmani->golongan_darah ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-bold opacity-50 uppercase mb-1">Jumlah Saudara</p>
                        <p class="text-indigo-100 font-black">{{ $siswa->dataPeriodik->jml_saudara_kandung ?? $siswa->jml_saudara_kandung ?? '0' }} <span class="text-[10px] font-medium opacity-60">Kandung</span> &bull; {{ $siswa->dataPeriodik->jml_saudara_tiri ?? '0' }} <span class="text-[10px] font-medium opacity-60">Tiri</span></p>
                    </div>
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
                        <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ $siswa->keadaanJasmani->nama_riwayat_penyakit ?? $siswa->riwayat_penyakit ?? 'Tidak ada riwayat penyakit signifikan' }}</p>
                    </div>
                    <div class="p-4 bg-amber-50/50 rounded-2xl border border-amber-100/50">
                        <p class="text-[10px] font-bold text-amber-500 uppercase mb-1">Kelainan Jasmani / Kebutuhan Khusus</p>
                        <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ $siswa->keadaanJasmani->kelainan_jasmani ?? $siswa->kebutuhan_khusus ?? 'Tidak ada' }}</p>
                    </div>
                </div>
            </div>

             <!-- SECTION: BANTUAN & BEASISWA -->
             <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-emerald-500 rounded-full"></span> Bantuan & Beasiswa
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-slate-50/50 rounded-xl border border-slate-100">
                        <div>
                            <p class="text-[0.65rem] font-bold text-slate-500 uppercase">Penerima KIP</p>
                            <p class="text-xs font-black text-slate-700">{{ $siswa->penerima_kip ?? 'Tidak' }}</p>
                        </div>
                        @if($siswa->nomor_kip)
                            <div class="text-right">
                                <p class="text-[0.65rem] font-bold text-slate-400 font-mono tracking-tighter">{{ $siswa->nomor_kip }}</p>
                                <p class="text-[8px] text-slate-400 italic">a.n {{ $siswa->nama_di_kip }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center justify-between p-3 bg-slate-50/50 rounded-xl border border-slate-100">
                        <div>
                            <p class="text-[0.65rem] font-bold text-slate-500 uppercase">Layak PIP</p>
                            <p class="text-xs font-black text-slate-700">{{ $siswa->layak_pip ?? 'Tidak' }}</p>
                        </div>
                        @if($siswa->alasan_layak_pip)
                             <p class="text-[10px] text-slate-400 max-w-[120px] text-right italic">{{ $siswa->alasan_layak_pip }}</p>
                        @endif
                    </div>
                    <div class="flex items-center justify-between p-3 bg-slate-50/50 rounded-xl border border-slate-100">
                        <div>
                            <p class="text-[0.65rem] font-bold text-slate-500 uppercase">Penerima KPS</p>
                            <p class="text-xs font-black text-slate-700">{{ $siswa->penerima_kps ?? 'Tidak' }}</p>
                        </div>
                        @if($siswa->no_kps)
                             <p class="text-[10px] text-slate-400 font-mono tracking-tighter">{{ $siswa->no_kps }}</p>
                        @endif
                    </div>
                    @if($siswa->nomor_kks)
                    <div class="flex items-center justify-between p-3 bg-slate-50/50 rounded-xl border border-slate-100">
                        <p class="text-[0.65rem] font-bold text-slate-500 uppercase">Nomor KKS</p>
                        <p class="text-xs font-black text-slate-700 font-mono">{{ $siswa->nomor_kks }}</p>
                    </div>
                    @endif
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
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-1.5">No. KK</p>
                            <p class="text-base font-bold text-slate-700 font-mono">{{ $siswa->no_kk ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tempat, Tanggal Lahir</p>
                            <p class="text-base font-bold text-slate-700 capitalize">{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') : '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-1.5">Jenis Kelamin</p>
                            <p class="text-base font-bold text-slate-700">{{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
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
                        <div class="space-y-1">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-1.5">No. Reg. Akta Lahir</p>
                            <p class="text-sm font-bold text-slate-700 font-mono overflow-x-auto">{{ $siswa->no_registrasi_akta_lahir ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: ALAMAT & LOKASI -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-10">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.15em] mb-10 flex items-center gap-3">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                    Alamat, Kontak & Lokasi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-1.5">Alamat Lengkap</p>
                            <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ $siswa->dataPeriodik->alamat_tinggal ?? $siswa->alamat ?? '-' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-slate-50 rounded-2xl">
                                <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Bertempat Tinggal Pada</p>
                                <p class="text-xs font-bold text-slate-600">{{ $siswa->dataPeriodik->bertempat_tinggal_pada ?? $siswa->jenis_tinggal ?? '-' }}</p>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-2xl">
                                <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Transportasi</p>
                                <p class="text-xs font-bold text-slate-600">{{ $siswa->alat_transportasi ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100/50 flex items-center gap-4">
                            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-indigo-400 uppercase tracking-tighter leading-none mb-1">Koordinat Lintang/Bujur</p>
                                <p class="text-xs font-bold text-indigo-700 font-mono">{{ $siswa->lintang ?? '0' }}, {{ $siswa->bujur ?? '0' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-3">Kontak Siswa / Orang Tua</p>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div>
                                    <p class="text-sm font-bold text-slate-700">{{ $siswa->hp ?? '-' }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                                    <p class="text-sm font-bold text-slate-700">{{ $siswa->email ?? '-' }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div>
                                    <p class="text-sm font-bold text-slate-700 italic opacity-50">Tlp: {{ $siswa->telepon ?? '-' }}</p>
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
                    $ayah = $siswa->dataOrangTua->where('jenis', 'Ayah')->first();
                    $ibu = $siswa->dataOrangTua->where('jenis', 'Ibu')->first();
                    $wali = $siswa->dataOrangTua->where('jenis', 'Wali')->first();
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
                                <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Nama Ayah</p><p class="text-sm font-bold text-slate-800">{{ $ayah->nama ?? $siswa->nama_ayah ?? '-' }}</p></div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Thn Lahir</p><p class="text-xs font-bold text-slate-700">{{ $siswa->tahun_lahir_ayah ?? '-' }}</p></div>
                                    <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">NIK Ayah</p><p class="text-xs font-bold text-slate-700 font-mono tracking-tighter">{{ $siswa->nik_ayah ?? '-' }}</p></div>
                                </div>
                                <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Pendidikan</p><p class="text-xs font-bold text-slate-700">{{ $ayah->pendidikan_terakhir ?? $siswa->jenjang_pendidikan_ayah ?? '-' }}</p></div>
                                <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Pekerjaan</p><p class="text-xs font-bold text-slate-700">{{ $ayah->pekerjaan ?? $siswa->pekerjaan_ayah ?? '-' }}</p></div>
                                <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Penghasilan</p><p class="text-xs font-bold text-indigo-600">{{ $siswa->penghasilan_ayah ?? '-' }}</p></div>
                            </div>
                        </div>

                        <!-- Ibu -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center font-black">I</div>
                                <h4 class="font-black text-slate-800 uppercase text-xs tracking-widest">Ibu Kandung</h4>
                            </div>
                            <div class="space-y-4">
                                <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Nama Ibu</p><p class="text-sm font-bold text-slate-800">{{ $ibu->nama ?? $siswa->nama_ibu ?? '-' }}</p></div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Thn Lahir</p><p class="text-xs font-bold text-slate-700">{{ $siswa->tahun_lahir_ibu ?? '-' }}</p></div>
                                    <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">NIK Ibu</p><p class="text-xs font-bold text-slate-700 font-mono tracking-tighter">{{ $siswa->nik_ibu ?? '-' }}</p></div>
                                </div>
                                <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Pendidikan</p><p class="text-xs font-bold text-slate-700">{{ $ibu->pendidikan_terakhir ?? $siswa->jenjang_pendidikan_ibu ?? '-' }}</p></div>
                                <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Pekerjaan</p><p class="text-xs font-bold text-slate-700">{{ $ibu->pekerjaan ?? $siswa->pekerjaan_ibu ?? '-' }}</p></div>
                                <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Penghasilan</p><p class="text-xs font-bold text-rose-600">{{ $siswa->penghasilan_ibu ?? '-' }}</p></div>
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
                 @if($wali || $siswa->nama_wali)
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Nama Wali</p><p class="text-sm font-bold text-slate-800">{{ $wali->nama ?? $siswa->nama_wali ?? '-' }}</p></div>
                    <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Status Hubungan</p><p class="text-xs font-bold text-slate-700">{{ $wali->status_hubungan_wali ?? '-' }}</p></div>
                    <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">NIK Wali</p><p class="text-xs font-bold text-slate-700 font-mono">{{ $siswa->nik_wali ?? '-' }}</p></div>
                    <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Pendidikan</p><p class="text-xs font-bold text-slate-700">{{ $wali->pendidikan_terakhir ?? $siswa->jenjang_pendidikan_wali ?? '-' }}</p></div>
                    <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Pekerjaan</p><p class="text-xs font-bold text-slate-700">{{ $wali->pekerjaan ?? $siswa->pekerjaan_wali ?? '-' }}</p></div>
                    <div><p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Penghasilan</p><p class="text-xs font-bold text-emerald-600">{{ $siswa->penghasilan_wali ?? '-' }}</p></div>
                 </div>
                 @else
                 <div class="py-6 flex flex-col items-center justify-center text-center opacity-40">
                    <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.268 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-xs font-bold">Data wali tidak diisi (Tinggal dengan Orang Tua)</p>
                 </div>
                 @endif
            </div>

            <!-- SECTION 5: ADMINISTRASI, AKADEMIK & BANK -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Registrasi & Akademik -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-10 flex flex-col justify-between">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-8">Registrasi & Sekolah</h3>
                    <div class="space-y-4">
                        <div class="flex flex-col gap-1 border-l-4 border-slate-50 pl-4 py-1">
                            <span class="text-[9px] font-black text-slate-400 uppercase">Sekolah Asal</span>
                            <span class="text-sm font-bold text-slate-700">{{ $siswa->sekolah_asal ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-50 p-3 rounded-2xl">
                             <span class="text-xs font-bold text-slate-400 uppercase">SKHUN</span>
                             <span class="text-xs font-black text-slate-800 font-mono tracking-tighter">{{ $siswa->skhun ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-50 p-3 rounded-2xl">
                             <span class="text-xs font-bold text-slate-400 uppercase">No. Peserta UN</span>
                             <span class="text-xs font-black text-slate-800 font-mono tracking-tighter">{{ $siswa->no_peserta_un ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-50 p-3 rounded-2xl">
                             <span class="text-xs font-bold text-slate-400 uppercase">No. Seri Ijazah</span>
                             <span class="text-xs font-black text-slate-800 font-mono tracking-tighter">{{ $siswa->no_seri_ijazah ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Perbankan -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-[2rem] shadow-xl p-10 text-white relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full"></div>
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xs font-black uppercase tracking-widest opacity-60">Informasi Perbankan</h3>
                        <svg class="w-6 h-6 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    @if($siswa->nomor_rekening_bank)
                    <div class="space-y-6 relative z-10">
                        <div>
                            <p class="text-[9px] font-black uppercase opacity-40 mb-1">Nama Bank</p>
                            <p class="text-lg font-black tracking-tight">{{ $siswa->bank }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase opacity-40 mb-1">Nomor Rekening</p>
                            <p class="text-2xl font-black font-mono tracking-[0.2em]">{{ $siswa->nomor_rekening_bank }}</p>
                        </div>
                        <div class="pt-4 border-t border-white/10">
                            <p class="text-[9px] font-black uppercase opacity-40 mb-1">Atas Nama</p>
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-300">{{ $siswa->rekening_atas_nama ?? $siswa->nama }}</p>
                        </div>
                    </div>
                    @else
                    <div class="h-full flex flex-col items-center justify-center text-center opacity-30 py-10">
                         <p class="text-xs font-bold">Data transaksi/rekening bank belum tercatat.</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
