@extends('layouts.app')

@section('title', 'Buku Induk — ' . $siswa->nama)
@section('header_title', 'Buku Induk Siswa')
@section('breadcrumb')
    <a href="{{ route('buku-induk.index') }}" class="hover:text-indigo-600 transition-colors">Buku Induk</a>
    <span class="text-slate-300 mx-1">/</span>
    <span class="text-slate-800 font-semibold italic">{{ $siswa->nama }}</span>
@endsection

@section('content')
<div class="space-y-8 max-w-6xl mx-auto" x-data="{ tab: '{{ request('tab', 'identitas') }}' }">

    {{-- Back Button --}}
    <a href="{{ route('buku-induk.index') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-b from-white to-slate-100 hover:to-white rounded-xl text-slate-700 text-[10px] font-black uppercase tracking-wider transition-all shadow-sm border border-white group w-max">
        <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>

    {{-- Toast --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-5"
         class="fixed z-50 bottom-8 right-8 bg-slate-800 text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3 font-semibold text-sm">
        <div class="flex-shrink-0 bg-emerald-500 rounded-full p-1.5"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>
        <p>{{ session('success') }}</p>
        <button type="button" @click="show = false" class="ml-4 text-slate-400 hover:text-white transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    @endif

    {{-- Hero Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="h-3 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500"></div>
        <div class="p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                @if($bukuInduk->foto_1)
                <img src="{{ Storage::url($bukuInduk->foto_1) }}" class="w-20 h-20 rounded-2xl object-cover shadow-inner border-2 border-white">
                @else
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 text-indigo-700 flex items-center justify-center font-black text-2xl shadow-inner">
                    {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                </div>
                @endif
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h2 class="text-2xl font-black text-slate-800 tracking-tight">{{ $siswa->nama }}</h2>
                        @php
                            $statusColor = match($siswa->status) {
                                'Aktif' => 'bg-emerald-100 text-emerald-700',
                                'Lulus' => 'bg-sky-100 text-sky-700',
                                'Keluar/Mutasi' => 'bg-rose-100 text-rose-700',
                                default => 'bg-slate-100 text-slate-600',
                            };
                        @endphp
                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $statusColor }}">{{ $siswa->status ?? 'Aktif' }}</span>
                    </div>
                    <div class="flex flex-wrap gap-3 text-sm">
                        <span class="flex items-center gap-1.5 text-slate-500 font-medium">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 014 0"/></svg>
                            NISN: <span class="font-mono font-bold text-slate-700">{{ $siswa->nisn ?? '-' }}</span>
                        </span>
                        <span class="flex items-center gap-1.5 text-slate-500 font-medium">
                            NIS: <span class="font-bold text-slate-700">{{ $siswa->nipd ?? '-' }}</span>
                        </span>
                        <span class="flex items-center gap-1.5 text-slate-500 font-medium">
                            Tingkat: <span class="font-bold text-slate-700">{{ $siswa->tingkat_kelas ?? '-' }}</span>
                        </span>
                    </div>
                </div>
            </div>
            {{-- Actions --}}
            <div class="flex gap-3 flex-shrink-0 flex-wrap">
                <a href="{{ route('siswas.show', $siswa->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-sky-50 hover:border-sky-200 hover:text-sky-700 transition-all shadow-sm">
                    <svg class="w-4 h-4 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Data Pokok Siswa
                </a>
                <a href="javascript:void(0)" onclick="confirmPrint('main')"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-200 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Buku Induk
                </a>
                <a href="javascript:void(0)" onclick="confirmPrint('prestasi')"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Prestasi Belajar
                </a>
                @hasanyrole('Super Admin|Operator|Tata Usaha')
                <a href="{{ route('buku-induk.edit', $siswa->nisn) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-yellow-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-200 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Lengkapi Data
                </a>
                @endhasanyrole
            </div>
        </div>

        {{-- Progress Bar + Motivational Banner --}}
        @php
            $progressBg = $kelengkapan >= 80 ? 'from-emerald-500 to-teal-400' : ($kelengkapan >= 40 ? 'from-amber-500 to-yellow-400' : 'from-rose-500 to-pink-400');
            $progressBorder = $kelengkapan >= 80 ? 'border-emerald-200 bg-emerald-50' : ($kelengkapan >= 40 ? 'border-amber-200 bg-amber-50' : 'border-rose-200 bg-rose-50');
            $progressText = $kelengkapan >= 80 ? 'text-emerald-700' : ($kelengkapan >= 40 ? 'text-amber-700' : 'text-rose-700');
            $emoji = $kelengkapan >= 80 ? '🎉' : ($kelengkapan >= 40 ? '💪' : '🚀');
            $motivasi = $kelengkapan >= 100 ? 'Luar biasa! Semua data sudah terlengkapi dengan sempurna! 🏆'
                : ($kelengkapan >= 80 ? 'Hampir sempurna! Sedikit lagi data ini akan lengkap sepenuhnya.'
                : ($kelengkapan >= 40 ? 'Sudah separuh jalan! Ayo lengkapi sisa datanya supaya administrasi makin rapi.'
                : 'Yuk mulai lengkapi data Buku Induk ini! Setiap informasi yang terisi adalah investasi administrasi yang berharga.'));
        @endphp
        <div class="px-8 py-5 border-b border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-black text-slate-500 uppercase tracking-widest">Kelengkapan Data</span>
                        <span class="text-sm font-black {{ $progressText }}">{{ $kelengkapan }}%</span>
                    </div>
                    <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r {{ $progressBg }} transition-all duration-700" style="width: {{ $kelengkapan }}%"></div>
                    </div>
                </div>
            </div>
            <div class="mt-3 flex items-start gap-3 px-4 py-3 rounded-xl border {{ $progressBorder }}">
                <span class="text-xl flex-shrink-0">{{ $emoji }}</span>
                <div>
                    <p class="text-sm font-bold {{ $progressText }}">{{ $motivasi }}</p>
                    @if($kelengkapan < 100)
                    @hasanyrole('Super Admin|Operator|Tata Usaha')
                    <a href="{{ route('buku-induk.edit', $siswa->nisn) }}" class="inline-flex items-center gap-1.5 mt-2 text-xs font-black {{ $progressText }} hover:underline transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Lengkapi Sekarang →
                    </a>
                    @endhasanyrole
                    @endif
                </div>
            </div>
        </div>
        {{-- Tab Navigation --}}
        <div class="border-t border-slate-100 bg-slate-50/50 px-8 flex gap-1 overflow-x-auto">
            @foreach([
                'identitas' => 'Identitas Murid',
                'orang_tua' => 'Orang Tua / Wali',
                'periodik' => 'Data Periodik',
                'pendidikan' => 'Pendidikan Sebelumnya',
                'jasmani' => 'Keadaan Jasmani',
                'beasiswa' => 'Beasiswa',
                'riwayat' => 'Riwayat Sekolah',
                'photo' => 'Foto Siswa',
                'akademik' => 'Prestasi Akademik',
                'ekskul' => 'Ekstrakurikuler',
                'jejak' => 'Jejak Rombel',
            ] as $key => $label)
            <button @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'border-b-2 border-indigo-600 text-indigo-700 font-black' : 'text-slate-500 hover:text-slate-700'"
                    class="px-5 py-4 text-[13px] font-bold transition-all cursor-pointer tracking-wide uppercase whitespace-nowrap">
                {{ $label }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- ─── TAB: IDENTITAS MURID ─── --}}
    <div x-show="tab === 'identitas'" x-transition>
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 relative bg-slate-50/50">
                <p class="absolute -top-3 left-6 px-2 bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-full border border-slate-200">Data Identitas Murid</p>
                @php
                    $identitasFields = [
                        '1. NIS' => $siswa->nipd ?? '—',
                        '2. NISN' => $siswa->nisn ?? '—',
                        '3. NIK' => $siswa->nik ?? '—',
                        '4. Nama Lengkap' => $siswa->nama ?? '—',
                        '5. Nama Panggilan' => $siswa->nama_panggilan ?? '—',
                        '6. Jenis Kelamin' => ($siswa->jk == 'L') ? 'Laki-laki' : (($siswa->jk == 'P') ? 'Perempuan' : '—'),
                        '7. Tempat, Tanggal Lahir' => ($siswa->tempat_lahir ?? '—') . ', ' . ($siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') : '—'),
                        '8. Agama' => $siswa->agama ?? '—',
                        '9. Kewarganegaraan' => $siswa->kewarganegaraan ?? '—',
                        '10. No. Telepon' => $siswa->telepon ?? $siswa->hp ?? '—',
                    ];
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-5 gap-x-10">
                    @foreach($identitasFields as $label => $value)
                    <div class="space-y-0.5">
                        <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</p>
                        <p class="font-bold text-slate-700 text-sm">{{ $value }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ─── TAB: ORANG TUA / WALI ─── --}}
    <div x-show="tab === 'orang_tua'" x-transition>
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 relative bg-slate-50/50">
                <p class="absolute -top-3 left-6 px-2 bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-full border border-slate-200">Data Orang Tua / Wali</p>
                @php
                    $ayah = $siswa->dataOrangTua ? $siswa->dataOrangTua->where('jenis', 'Ayah')->first() : null;
                    $ibu = $siswa->dataOrangTua ? $siswa->dataOrangTua->where('jenis', 'Ibu')->first() : null;
                    $wali = $siswa->dataOrangTua ? $siswa->dataOrangTua->where('jenis', 'Wali')->first() : null;
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-8">
                    {{-- Ayah --}}
                    <div class="space-y-4">
                        <h4 class="font-black text-slate-700 border-b border-slate-200 pb-2 flex items-center gap-2"><span class="w-1 h-5 bg-blue-500 rounded-full"></span>Data Ayah</h4>
                        @foreach([
                            '1. Nama Lengkap' => $ayah->nama ?? $siswa->nama_ayah ?? '—',
                            '2. Pendidikan Terakhir' => $ayah->pendidikan_terakhir ?? $siswa->jenjang_pendidikan_ayah ?? '—',
                            '3. Pekerjaan' => $ayah->pekerjaan ?? $siswa->pekerjaan_ayah ?? '—',
                        ] as $label => $value)
                        <div class="space-y-0.5">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</p>
                            <p class="font-bold text-slate-700 text-sm">{{ $value }}</p>
                        </div>
                        @endforeach
                    </div>
                    {{-- Ibu --}}
                    <div class="space-y-4">
                        <h4 class="font-black text-slate-700 border-b border-slate-200 pb-2 flex items-center gap-2"><span class="w-1 h-5 bg-pink-500 rounded-full"></span>Data Ibu</h4>
                        @foreach([
                            '1. Nama Lengkap' => $ibu->nama ?? $siswa->nama_ibu ?? '—',
                            '2. Pendidikan Terakhir' => $ibu->pendidikan_terakhir ?? $siswa->jenjang_pendidikan_ibu ?? '—',
                            '3. Pekerjaan' => $ibu->pekerjaan ?? $siswa->pekerjaan_ibu ?? '—',
                        ] as $label => $value)
                        <div class="space-y-0.5">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</p>
                            <p class="font-bold text-slate-700 text-sm">{{ $value }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                {{-- Wali --}}
                <div class="border-t border-dashed border-slate-200 pt-8">
                    <h4 class="font-black text-slate-700 border-b border-slate-200 pb-2 flex items-center gap-2 mb-4"><span class="w-1 h-5 bg-amber-500 rounded-full"></span>Data Wali</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach([
                            '1. Nama Wali' => $wali->nama ?? $siswa->nama_wali ?? '—',
                            '2. Hubungan' => $wali->status_hubungan_wali ?? '—',
                            '3. Pendidikan' => $wali->pendidikan_terakhir ?? $siswa->jenjang_pendidikan_wali ?? '—',
                            '4. Pekerjaan' => $wali->pekerjaan ?? $siswa->pekerjaan_wali ?? '—',
                        ] as $label => $value)
                        <div class="space-y-0.5">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</p>
                            <p class="font-bold text-slate-700 text-sm">{{ $value }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── TAB: DATA PERIODIK ─── --}}
    <div x-show="tab === 'periodik'" x-transition>
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 relative bg-slate-50/50">
                <p class="absolute -top-3 left-6 px-2 bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-full border border-slate-200">Data Periodik Siswa</p>
                @php
                    $periodikFields = [
                        '1. Jumlah Saudara Kandung' => $siswa->dataPeriodik->jml_saudara_kandung ?? $siswa->jml_saudara_kandung ?? 0,
                        '2. Jumlah Saudara Tiri' => $siswa->dataPeriodik->jml_saudara_tiri ?? 0,
                        '3. Jumlah Saudara Angkat' => $siswa->dataPeriodik->jml_saudara_angkat ?? 0,
                        '4. Bahasa Sehari-hari' => $siswa->dataPeriodik->bahasa_sehari_hari ?? '—',
                        '5. Alamat Tinggal' => $siswa->dataPeriodik->alamat_tinggal ?? $siswa->alamat ?? '—',
                        '6. Bertempat Tinggal Pada' => $siswa->dataPeriodik->bertempat_tinggal_pada ?? $siswa->jenis_tinggal ?? '—',
                        '7. Jarak ke Sekolah' => ($siswa->dataPeriodik->jarak_tempat_tinggal_ke_sekolah ?? $siswa->jarak_rumah_ke_sekolah_km ?? '—') . ' km',
                    ];
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-5 gap-x-10">
                    @foreach($periodikFields as $label => $value)
                    <div class="space-y-0.5 {{ Str::contains($label, 'Alamat') ? 'md:col-span-2 lg:col-span-3' : '' }}">
                        <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</p>
                        <p class="font-bold text-slate-700 text-sm">{{ $value }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ─── TAB: PENDIDIKAN SEBELUMNYA ─── --}}
    <div x-show="tab === 'pendidikan'" x-transition>
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 relative bg-slate-50/50">
                <p class="absolute -top-3 left-6 px-2 bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-full border border-slate-200">Data Pendidikan Sebelumnya</p>
                
                {{-- A. Siswa Baru --}}
                <div class="mb-8">
                    <h4 class="font-black text-slate-700 border-b border-slate-200 pb-2 flex items-center gap-2 mb-4"><span class="w-1 h-5 bg-emerald-500 rounded-full"></span>A. Masuk Menjadi Siswa Baru</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-5 gap-x-10">
                        @foreach([
                            '1. Asal Siswa' => $bukuInduk->asal_masuk_sekolah ?? '—',
                            '2. Nama Sekolah Asal' => $bukuInduk->nama_tk_asal ?? $siswa->sekolah_asal ?? '—',
                            '3. Tanggal Masuk' => $bukuInduk->tgl_masuk_sekolah ? $bukuInduk->tgl_masuk_sekolah->format('d F Y') : '—',
                        ] as $label => $value)
                        <div class="space-y-0.5">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</p>
                            <p class="font-bold text-slate-700 text-sm">{{ $value }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- B. Pindahan --}}
                <div class="border-t border-dashed border-slate-200 pt-8">
                    <h4 class="font-black text-slate-700 border-b border-slate-200 pb-2 flex items-center gap-2 mb-4"><span class="w-1 h-5 bg-amber-500 rounded-full"></span>B. Pindahan Dari Sekolah Lain</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-y-5 gap-x-10">
                        @foreach([
                            '1. Nama Sekolah Asal' => $bukuInduk->pindah_dari ?? '—',
                            '2. Dari Kelas' => $bukuInduk->kelas_pindah_masuk ? 'Kelas ' . $bukuInduk->kelas_pindah_masuk : '—',
                            '3. Diterima Tanggal' => $bukuInduk->tgl_pindah_masuk ? $bukuInduk->tgl_pindah_masuk->format('d F Y') : '—',
                            '4. Di Kelas' => $bukuInduk->kelas_pindah_masuk ? 'Kelas ' . $bukuInduk->kelas_pindah_masuk : '—',
                        ] as $label => $value)
                        <div class="space-y-0.5">
                            <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</p>
                            <p class="font-bold text-slate-700 text-sm">{{ $value }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── TAB: KEADAAN JASMANI ─── --}}
    <div x-show="tab === 'jasmani'" x-transition>
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 relative bg-slate-50/50">
                <p class="absolute -top-3 left-6 px-2 bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-full border border-slate-200">Data Keadaan Jasmani</p>
                @php
                    $jasmaniFields = [
                        '1. Berat Badan' => ($siswa->keadaanJasmani->berat_badan ?? '—') . ' kg',
                        '2. Tinggi Badan' => ($siswa->keadaanJasmani->tinggi_badan ?? '—') . ' cm',
                        '3. Golongan Darah' => $siswa->keadaanJasmani->golongan_darah ?? '—',
                        '4. Riwayat Penyakit' => $siswa->keadaanJasmani->nama_riwayat_penyakit ?? 'Tidak ada',
                        '5. Kelainan Jasmani' => $siswa->keadaanJasmani->kelainan_jasmani ?? 'Tidak ada',
                    ];
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-5 gap-x-10">
                    @foreach($jasmaniFields as $label => $value)
                    <div class="space-y-0.5 {{ Str::contains($label, 'Riwayat') || Str::contains($label, 'Kelainan') ? 'md:col-span-2 lg:col-span-3' : '' }}">
                        <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</p>
                        <p class="font-bold text-slate-700 text-sm">{{ $value }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ─── TAB: BEASISWA ─── --}}
    <div x-show="tab === 'beasiswa'" x-transition x-data="{ showBeasiswaModal: false }">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-slate-800">Riwayat Beasiswa</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Catatan beasiswa yang pernah diterima siswa (PIP, KIP, Daerah, Swasta, dll.)</p>
                </div>
                @hasanyrole('Super Admin|Operator|Tata Usaha')
                <button type="button" @click="showBeasiswaModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-200 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah
                </button>
                @endhasanyrole
            </div>

            <div class="p-8">
                @if($siswa->beasiswa && $siswa->beasiswa->count() > 0)
                <div class="border-2 border-dashed border-slate-200 rounded-2xl overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50">
                            <tr class="border-b border-slate-200 text-slate-500 text-[0.7rem] uppercase font-black tracking-widest">
                                <th class="px-6 py-3 w-12 text-center border-r border-slate-100">No</th>
                                <th class="px-6 py-3 border-r border-slate-100">Jenis Beasiswa</th>
                                <th class="px-6 py-3 w-28 border-r border-slate-100">Tahun</th>
                                <th class="px-6 py-3">Keterangan</th>
                                @hasanyrole('Super Admin|Operator|Tata Usaha')
                                <th class="px-6 py-3 w-20 text-center">Aksi</th>
                                @endhasanyrole
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($siswa->beasiswa as $i => $bsw)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3 text-center text-sm font-bold text-slate-400 border-r border-slate-100">{{ $i + 1 }}</td>
                                <td class="px-6 py-3 border-r border-slate-100">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-black rounded-lg">{{ $bsw->jenis_beasiswa }}</span>
                                </td>
                                <td class="px-6 py-3 font-bold text-slate-700 text-sm border-r border-slate-100">{{ $bsw->tahun ?? '—' }}</td>
                                <td class="px-6 py-3 text-sm text-slate-600">{{ $bsw->keterangan ?? '—' }}</td>
                                @hasanyrole('Super Admin|Operator|Tata Usaha')
                                <td class="px-6 py-3 text-center">
                                    <form method="POST" action="{{ url('/siswas/' . $siswa->id . '/beasiswa/' . $bsw->id) }}" class="inline"
                                          onsubmit="return false;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="confirmDeleteBeasiswa(this)"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                                @endhasanyrole
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-400 rounded-full flex items-center justify-center mb-4 border border-emerald-100">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-500">Belum ada riwayat beasiswa dicatat.</p>
                    @hasanyrole('Super Admin|Operator|Tata Usaha')
                    <button type="button" @click="showBeasiswaModal = true"
                            class="mt-3 text-xs font-bold text-emerald-600 hover:text-emerald-700 hover:underline transition-colors">
                        + Tambah data beasiswa
                    </button>
                    @endhasanyrole
                </div>
                @endif
            </div>
        </div>

        {{-- Modal Tambah Beasiswa --}}
        <div x-show="showBeasiswaModal" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             @keydown.escape.window="showBeasiswaModal = false">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showBeasiswaModal = false"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 z-10"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-black text-slate-800">Tambah Beasiswa</h3>
                    <button type="button" @click="showBeasiswaModal = false"
                            class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form method="POST" action="{{ url('/siswas/' . $siswa->id . '/beasiswa') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            Jenis Beasiswa <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="jenis_beasiswa" required maxlength="255"
                               placeholder="Contoh: PIP, KIP, Daerah, Swasta, Lainnya"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            Tahun <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="tahun" required maxlength="10"
                               placeholder="Contoh: 2024 atau 2023/2024"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Keterangan</label>
                        <textarea name="keterangan" rows="3"
                                  placeholder="Keterangan tambahan (opsional)..."
                                  class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition resize-none"></textarea>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showBeasiswaModal = false"
                                class="flex-1 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-200 transition-all hover:-translate-y-0.5">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ─── TAB: RIWAYAT SEKOLAH (REGISTRASI & MUTASI) ─── --}}
    <div x-show="tab === 'riwayat'" x-transition x-data="{ showRegistrasiModal: false }">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-slate-800">Riwayat Registrasi &amp; Mutasi</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Catatan pendaftaran, mutasi masuk/keluar, dan kelulusan siswa</p>
                </div>
                @hasanyrole('Super Admin|Operator|Tata Usaha')
                <button type="button" @click="showRegistrasiModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah
                </button>
                @endhasanyrole
            </div>

            <div class="p-8">
                @if($siswa->registrasi && $siswa->registrasi->count() > 0)
                <div class="border-2 border-dashed border-slate-200 rounded-2xl overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50">
                            <tr class="border-b border-slate-200 text-slate-500 text-[0.7rem] uppercase font-black tracking-widest">
                                <th class="px-5 py-3 w-12 text-center border-r border-slate-100">No</th>
                                <th class="px-5 py-3 border-r border-slate-100">Jenis</th>
                                <th class="px-5 py-3 w-36 border-r border-slate-100">Tanggal</th>
                                <th class="px-5 py-3 border-r border-slate-100">Tujuan Sekolah</th>
                                <th class="px-5 py-3 w-32 border-r border-slate-100">Tujuan Kelas</th>
                                <th class="px-5 py-3">Alasan / Catatan</th>
                                @hasanyrole('Super Admin|Operator|Tata Usaha')
                                <th class="px-5 py-3 w-20 text-center">Aksi</th>
                                @endhasanyrole
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($siswa->registrasi as $i => $reg)
                            @php
                                $regColor = match($reg->jenis_registrasi) {
                                    'Daftar Baru'   => 'bg-sky-100 text-sky-700',
                                    'Mutasi Masuk'  => 'bg-emerald-100 text-emerald-700',
                                    'Pindah Keluar' => 'bg-amber-100 text-amber-700',
                                    'Lulus'         => 'bg-purple-100 text-purple-700',
                                    default         => 'bg-slate-100 text-slate-600',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-3 text-center text-sm font-bold text-slate-400 border-r border-slate-100">{{ $i + 1 }}</td>
                                <td class="px-5 py-3 border-r border-slate-100">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-black rounded-lg {{ $regColor }}">{{ $reg->jenis_registrasi }}</span>
                                </td>
                                <td class="px-5 py-3 text-sm font-bold text-slate-700 border-r border-slate-100">
                                    {{ $reg->tanggal ? \Carbon\Carbon::parse($reg->tanggal)->format('d/m/Y') : '—' }}
                                </td>
                                <td class="px-5 py-3 text-sm text-slate-600 border-r border-slate-100">{{ $reg->tujuan_sekolah ?? '—' }}</td>
                                <td class="px-5 py-3 text-sm text-slate-600 border-r border-slate-100">{{ $reg->tujuan_kelas ?? '—' }}</td>
                                <td class="px-5 py-3 text-sm text-slate-600">{{ $reg->alasan_catatan ?? '—' }}</td>
                                @hasanyrole('Super Admin|Operator|Tata Usaha')
                                <td class="px-5 py-3 text-center">
                                    <form method="POST" action="{{ url('/siswas/' . $siswa->id . '/registrasi/' . $reg->id) }}" class="inline"
                                          onsubmit="return false;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="confirmDeleteRegistrasi(this)"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                                @endhasanyrole
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-14 h-14 bg-indigo-50 text-indigo-400 rounded-full flex items-center justify-center mb-4 border border-indigo-100">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-500">Belum ada catatan registrasi, mutasi, atau kelulusan dicatat.</p>
                    @hasanyrole('Super Admin|Operator|Tata Usaha')
                    <button type="button" @click="showRegistrasiModal = true"
                            class="mt-3 text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline transition-colors">
                        + Tambah data registrasi
                    </button>
                    @endhasanyrole
                </div>
                @endif
            </div>
        </div>

        {{-- Modal Tambah Registrasi --}}
        <div x-show="showRegistrasiModal" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             @keydown.escape.window="showRegistrasiModal = false">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showRegistrasiModal = false"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 z-10"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-black text-slate-800">Tambah Registrasi / Mutasi</h3>
                    <button type="button" @click="showRegistrasiModal = false"
                            class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form method="POST" action="{{ url('/siswas/' . $siswa->id . '/registrasi') }}" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">
                                Jenis Registrasi <span class="text-rose-500">*</span>
                            </label>
                            <select name="jenis_registrasi" required
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                <option value="" disabled selected>-- Pilih Jenis --</option>
                                <option value="Daftar Baru">Daftar Baru</option>
                                <option value="Mutasi Masuk">Mutasi Masuk</option>
                                <option value="Pindah Keluar">Pindah Keluar</option>
                                <option value="Lulus">Lulus</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">
                                Tanggal <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="tanggal" required
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Tujuan Sekolah</label>
                            <input type="text" name="tujuan_sekolah" maxlength="255"
                                   placeholder="Nama sekolah tujuan"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Tujuan Kelas</label>
                            <input type="text" name="tujuan_kelas" maxlength="50"
                                   placeholder="Contoh: Kelas 5A"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Alasan / Catatan</label>
                            <textarea name="alasan_catatan" rows="3"
                                      placeholder="Alasan perpindahan, keterangan kelulusan, atau catatan lain..."
                                      class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none"></textarea>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showRegistrasiModal = false"
                                class="flex-1 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ─── TAB: FOTO SISWA (READ ONLY) ─── --}}
    <div x-show="tab === 'photo'" x-transition>
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 relative bg-slate-50/50">
                <p class="absolute -top-3 left-6 px-2 bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-full border border-slate-200">Pas Photo Siswa</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3 text-center">
                        <p class="text-xs font-bold text-slate-500">Photo 1 (Buku Induk)</p>
                        <div class="w-48 h-64 mx-auto rounded-2xl border-2 border-dashed border-slate-200 bg-white flex items-center justify-center overflow-hidden">
                            @if($bukuInduk->foto_1)
                            <img src="{{ Storage::url($bukuInduk->foto_1) }}" class="w-full h-full object-cover">
                            @else
                            <div class="text-center p-4">
                                <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Belum ada foto</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-3 text-center">
                        <p class="text-xs font-bold text-slate-500">Photo 2 (Arsip Sekolah)</p>
                        <div class="w-48 h-64 mx-auto rounded-2xl border-2 border-dashed border-slate-200 bg-white flex items-center justify-center overflow-hidden">
                            @if($bukuInduk->foto_2)
                            <img src="{{ Storage::url($bukuInduk->foto_2) }}" class="w-full h-full object-cover">
                            @else
                            <div class="text-center p-4">
                                <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Belum ada foto</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @hasanyrole('Super Admin|Operator|Tata Usaha')
                <div class="mt-6 text-center">
                    <a href="{{ route('buku-induk.edit', $siswa->nisn) }}" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Ubah foto melalui halaman Lengkapi Data
                    </a>
                </div>
                @endhasanyrole
            </div>
        </div>
    </div>

    {{-- ─── TAB: PRESTASI AKADEMIK ─── --}}
    <div x-show="tab === 'akademik'" x-transition>
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-slate-800">Prestasi Belajar (Tahun Pelajaran Aktif)</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Catatan nilai, kepribadian, dan kehadiran pada semester saat ini</p>
                </div>
                @hasanyrole('Super Admin|Operator|Tata Usaha')
                <a href="{{ route('buku-induk.edit', $siswa->nisn) }}" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Input / Update Nilai
                </a>
                @endhasanyrole
            </div>

            <div class="p-8">
                @php
                    $activeSmtInt = strtolower($activeTahunPelajaran?->semester ?? '') == 'ganjil' ? 1 : 2;
                    $activePrestasi = null;
                    if ($currentRombel && $activeTahunPelajaran) {
                        $activePrestasi = $bukuInduk->prestasis()
                            ->where('kelas', $currentRombel->tingkat)
                            ->where('semester', $activeSmtInt)
                            ->first();
                    }
                @endphp

                @if(!$activePrestasi)
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mb-4 border border-slate-200">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-800 tracking-tight">Belum Ada Data</h4>
                        <p class="text-slate-500 font-medium mt-2 max-w-md">Siswa ini belum memiliki catatan nilai untuk Tahun Pelajaran aktif ({{ $activeTahunPelajaran?->tahun }} - {{ $activeTahunPelajaran?->semester }}) di Tingkat Kelas {{ $currentRombel?->tingkat ?? '—' }}.</p>
                    </div>
                @else
                    <div class="space-y-8">
                        {{-- IDENTITAS SEMESTER --}}
                        <div class="flex flex-wrap gap-4">
                            <span class="px-4 py-2 bg-indigo-50 border border-indigo-100 text-indigo-700 font-bold text-sm rounded-xl tracking-tight">Tahun Pelajaran: {{ $activeTahunPelajaran->tahun }}</span>
                            <span class="px-4 py-2 bg-indigo-50 border border-indigo-100 text-indigo-700 font-bold text-sm rounded-xl tracking-tight">Semester: {{ $activeTahunPelajaran->semester }} ({{ $activeSmtInt }})</span>
                            <span class="px-4 py-2 bg-indigo-50 border border-indigo-100 text-indigo-700 font-bold text-sm rounded-xl tracking-tight">Kelas: {{ $currentRombel->tingkat }}</span>
                        </div>

                        {{-- TABEL MATA PELAJARAN --}}
                        <div>
                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3">1. Penilaian Hasil Belajar</p>
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl overflow-hidden">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-slate-50">
                                        <tr class="border-b border-slate-200 text-slate-500 text-[0.7rem] uppercase font-black tracking-widest">
                                            <th class="px-6 py-3 w-16 text-center border-r border-slate-100">No</th>
                                            <th class="px-6 py-3 border-r border-slate-100">Nama Mata Pelajaran</th>
                                            <th class="px-6 py-3 w-48 text-center">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @foreach($mataPelajarans as $index => $mapel)
                                            @php
                                                $nilaiItem = $activePrestasi->nilais->where('mata_pelajaran_id', $mapel->id)->first();
                                            @endphp
                                            <tr class="hover:bg-slate-50/50 transition-colors">
                                                <td class="px-6 py-3 text-center text-sm font-bold text-slate-400 border-r border-slate-100">{{ $index + 1 }}</td>
                                                <td class="px-6 py-3 font-bold text-slate-700 text-sm border-r border-slate-100">{{ $mapel->nama }}</td>
                                                <td class="px-6 py-3 text-center font-bold {{ ($nilaiItem?->nilai ?? 0) < 65 && $nilaiItem?->nilai !== null ? 'text-rose-600' : 'text-slate-800' }}">
                                                    {{ $nilaiItem?->nilai ?? '—' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-slate-50 border-t border-slate-200">
                                        <tr>
                                            <td colspan="2" class="px-6 py-4 text-right font-black text-slate-600 uppercase text-xs tracking-wider border-r border-slate-100">Jumlah & Rata-rata</td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-center gap-3">
                                                    <span class="text-sm font-black text-slate-800" title="Jumlah Nilai">Σ {{ $activePrestasi->jumlah_nilai ?? 0 }}</span>
                                                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100" title="Rata-rata">Rata: {{ $activePrestasi->rata_rata ?? 0 }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            {{-- KEPRIBADIAN --}}
                            <div>
                                <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3">2. Kepribadian</p>
                                <div class="border-2 border-dashed border-slate-200 rounded-2xl overflow-hidden h-full">
                                    <table class="w-full text-left border-collapse">
                                        <thead class="bg-slate-50">
                                            <tr class="border-b border-slate-200 text-slate-500 text-[0.7rem] uppercase font-black tracking-widest">
                                                <th class="px-6 py-3 w-16 text-center border-r border-slate-100">No</th>
                                                <th class="px-6 py-3 border-r border-slate-100">Keterangan</th>
                                                <th class="px-6 py-3 w-32 text-center">Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            <tr class="hover:bg-slate-50/50 transition-colors">
                                                <td class="px-6 py-3 text-center text-sm font-bold text-slate-400 border-r border-slate-100">1</td>
                                                <td class="px-6 py-3 font-bold text-slate-700 text-sm border-r border-slate-100">Sikap</td>
                                                <td class="px-6 py-3 text-center font-bold text-slate-800">{{ $activePrestasi->sikap ?? '—' }}</td>
                                            </tr>
                                            <tr class="hover:bg-slate-50/50 transition-colors">
                                                <td class="px-6 py-3 text-center text-sm font-bold text-slate-400 border-r border-slate-100">2</td>
                                                <td class="px-6 py-3 font-bold text-slate-700 text-sm border-r border-slate-100">Kerajinan</td>
                                                <td class="px-6 py-3 text-center font-bold text-slate-800">{{ $activePrestasi->kerajinan ?? '—' }}</td>
                                            </tr>
                                            <tr class="hover:bg-slate-50/50 transition-colors">
                                                <td class="px-6 py-3 text-center text-sm font-bold text-slate-400 border-r border-slate-100">3</td>
                                                <td class="px-6 py-3 font-bold text-slate-700 text-sm border-r border-slate-100">Kerapihan</td>
                                                <td class="px-6 py-3 text-center font-bold text-slate-800">{{ $activePrestasi->kebersihan_kerapian ?? '—' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- KETIDAKHADIRAN --}}
                            <div>
                                <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3">3. Ketidakhadiran</p>
                                <div class="border-2 border-dashed border-slate-200 rounded-2xl overflow-hidden h-full">
                                    <table class="w-full text-left border-collapse">
                                        <thead class="bg-slate-50">
                                            <tr class="border-b border-slate-200 text-slate-500 text-[0.7rem] uppercase font-black tracking-widest">
                                                <th class="px-6 py-3 w-16 text-center border-r border-slate-100">No</th>
                                                <th class="px-6 py-3 border-r border-slate-100">Keterangan</th>
                                                <th class="px-6 py-3 w-32 text-center">Hari</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            <tr class="hover:bg-slate-50/50 transition-colors">
                                                <td class="px-6 py-3 text-center text-sm font-bold text-slate-400 border-r border-slate-100">1</td>
                                                <td class="px-6 py-3 font-bold text-slate-700 text-sm border-r border-slate-100">Sakit</td>
                                                <td class="px-6 py-3 text-center font-bold text-slate-800">{{ $activePrestasi->hadir_sakit ?? 0 }}</td>
                                            </tr>
                                            <tr class="hover:bg-slate-50/50 transition-colors">
                                                <td class="px-6 py-3 text-center text-sm font-bold text-slate-400 border-r border-slate-100">2</td>
                                                <td class="px-6 py-3 font-bold text-slate-700 text-sm border-r border-slate-100">Izin</td>
                                                <td class="px-6 py-3 text-center font-bold text-slate-800">{{ $activePrestasi->hadir_izin ?? 0 }}</td>
                                            </tr>
                                            <tr class="hover:bg-slate-50/50 transition-colors">
                                                <td class="px-6 py-3 text-center text-sm font-bold text-slate-400 border-r border-slate-100">3</td>
                                                <td class="px-6 py-3 font-bold text-slate-700 text-sm border-r border-slate-100">Tanpa Keterangan</td>
                                                <td class="px-6 py-3 text-center font-bold text-slate-800">{{ $activePrestasi->hadir_alpha ?? 0 }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- PERINGKAT & KENAIKAN --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-slate-50 p-8 rounded-3xl border border-slate-200 shadow-sm mt-15">
                            <div class="space-y-1.5 align-top border-b pb-4 md:border-b-0 md:pb-0 md:border-r border-slate-200 pl-2">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Peringkat / Ranking Kelas</label>
                                <p class="text-2xl font-black text-slate-800 tracking-tighter">{{ $activePrestasi->peringkat ?? '—' }}</p>
                            </div>
                            <div class="space-y-1.5 align-top pl-2 md:pl-4">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Keterangan Kenaikan Kelas</label>
                                <div>
                                    @if($activePrestasi->keterangan_kenaikan)
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-sm font-black tracking-wide border {{ $activePrestasi->keterangan_kenaikan == 'Naik' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-rose-100 text-rose-700 border-rose-200' }}">
                                        {{ $activePrestasi->keterangan_kenaikan }}
                                    </span>
                                    @else
                                    <span class="text-2xl font-black text-slate-300 tracking-tighter">—</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ─── TAB: EKSTRAKURIKULER ─── --}}
    <div x-show="tab === 'ekskul'" x-transition>
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-slate-800">Ekstrakurikuler</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Catatan nilai ekstrakurikuler per semester</p>
                </div>
                @hasanyrole('Super Admin|Operator|Tata Usaha')
                <a href="{{ route('buku-induk.edit', $siswa->nisn) }}" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Input Nilai (Via Tab Akademik)
                </a>
                @endhasanyrole
            </div>
            <div class="overflow-x-auto p-8">
                @php
                    // Get all unique ekstrakurikuler the student has ever taken to define columns
                    $studentEkskulIds = $siswa->prestasiEkstrakurikulers->pluck('ekstrakurikuler_id')->unique();
                    $activeEkskuls = $ekstrakurikulers->whereIn('id', $studentEkskulIds);
                @endphp
                @if($activeEkskuls->isEmpty())
                    <div class="text-center py-6">
                        <p class="text-sm font-bold text-slate-400">Siswa ini belum pernah memiliki nilai Ekstrakurikuler.</p>
                    </div>
                @else
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50/80 text-slate-400 text-[0.65rem] uppercase font-extrabold tracking-widest text-center">
                        <tr>
                            <th class="px-6 py-3 text-left w-16">Kelas</th>
                            <th class="px-3 py-3 w-16 border-r border-slate-100">Semester</th>
                            @foreach($activeEkskuls as $ekskul)
                            <th class="px-3 py-3">{{ $ekskul->nama_ekstrakurikuler }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach(range(1, 6) as $kelas)
                        @foreach([1, 2] as $semester)
                        @php
                            $semesterEkskuls = $siswa->prestasiEkstrakurikulers->where('kelas', $kelas)->where('semester', $semester);
                        @endphp
                        <tr class="hover:bg-indigo-50/30 transition-colors {{ $semester == 1 ? '' : 'bg-slate-50/20' }}">
                            <td class="px-6 py-3 font-black text-slate-700">
                                @if($semester == 1)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-black">{{ $kelas }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-slate-500 font-medium text-center border-r border-slate-100">{{ $semester }}</td>
                            @foreach($activeEkskuls as $ekskul)
                                @php
                                    $predikat = $semesterEkskuls->where('ekstrakurikuler_id', $ekskul->id)->first()?->predikat;
                                @endphp
                                <td class="px-3 py-3 text-center font-bold text-slate-700">
                                    {{ $predikat ?? '—' }}
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

    {{-- ─── TAB: JEJAK ROMBEL ─── --}}
    <div x-show="tab === 'jejak'" x-transition>
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-800">Jejak Rombel & Tahun Pelajaran</h3>
                <p class="text-sm text-slate-500 mt-0.5">Riwayat penempatan kelas siswa di setiap sesi akademik aktif</p>
            </div>
            <div class="p-8">
                <div class="relative">
                    <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-slate-100"></div>
                    <div class="space-y-8">
                        @foreach($bukuInduk->riwayatSiswa() as $history)
                        <div class="relative pl-12">
                            <div class="absolute left-0 top-1.5 w-8 h-8 rounded-full bg-white border-4 border-indigo-600 shadow-sm z-10 flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-indigo-600"></div>
                            </div>
                            <div class="bg-slate-50/50 rounded-2xl p-5 border border-slate-100 hover:border-indigo-200 transition-colors group">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div>
                                        <p class="text-[0.65rem] font-bold text-indigo-600 uppercase tracking-widest mb-1">Tahun Pelajaran {{ $history->tahunPelajaran->tahun ?? '—' }}</p>
                                        <h4 class="text-lg font-black text-slate-800">Kelas / Rombel: <span class="text-indigo-700">{{ $history->rombel_saat_ini ?? $history->rombel->nama ?? '—' }}</span></h4>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
                                            <p class="text-[0.65rem] font-bold text-slate-400 uppercase leading-none mb-1">Semester</p>
                                            <p class="text-sm font-black text-slate-700">{{ $history->tahunPelajaran->semester ?? '—' }}</p>
                                        </div>
                                        @php
                                            $hStatusColor = match($history->status) {
                                                'Aktif' => 'bg-emerald-100 text-emerald-700',
                                                'Lulus' => 'bg-sky-100 text-sky-700',
                                                'Keluar/Mutasi' => 'bg-rose-100 text-rose-700',
                                                default => 'bg-slate-100 text-slate-600',
                                            };
                                        @endphp
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg {{ $hStatusColor }}">{{ $history->status ?? 'Aktif' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDeleteBeasiswa(btn) {
    var form = btn.closest('form');
    Swal.fire({
        title: 'Hapus Data Beasiswa?',
        text: 'Data beasiswa yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then(function(result) {
        if (result.isConfirmed) {
            form.onsubmit = null;
            form.submit();
        }
    });
}

function confirmDeleteRegistrasi(btn) {
    var form = btn.closest('form');
    Swal.fire({
        title: 'Hapus Data Registrasi?',
        text: 'Data registrasi yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then(function(result) {
        if (result.isConfirmed) {
            form.onsubmit = null;
            form.submit();
        }
    });
}

function confirmPrint(type = 'main') {
    let kelengkapan = {{ $kelengkapan }};
    let printUrl = type === 'main' 
        ? "{{ route('buku-induk.print', $siswa->nisn) }}" 
        : "{{ route('buku-induk.print-prestasi', $siswa->nisn) }}";
    
    let title = type === 'main' ? 'Cetak Buku Induk' : 'Cetak Prestasi Belajar';

    if (kelengkapan < 100 && type === 'main') {
        Swal.fire({
            title: 'Data Belum Lengkap (100%)',
            text: 'Kelengkapan data buku induk saat ini baru ' + kelengkapan + '%. Dokumen PDF mungkin memiliki bagian yang kosong. Apakah Anda tetap ingin mencetak?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, ' + title,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.open(printUrl, "_blank");
            }
        });
    } else {
        window.open(printUrl, "_blank");
    }
}
</script>
@endsection
