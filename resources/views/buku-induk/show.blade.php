@extends('layouts.app')

@section('title', 'Buku Induk — ' . $siswa->nama)
@section('header_title', 'Buku Induk Siswa')
@section('breadcrumb')
    <a href="{{ route('buku-induk.index') }}" class="hover:text-indigo-600 transition-colors">Buku Induk</a>
    <span class="text-slate-300 mx-1">/</span>
    <span class="text-slate-800 font-semibold italic">{{ $siswa->nama }}</span>
@endsection

@section('content')
<div class="space-y-8 max-w-6xl mx-auto" x-data="{ tab: 'identitas' }">

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
            <div class="flex gap-3 flex-shrink-0">
                <a href="{{ route('buku-induk.print', $siswa->nisn) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak
                </a>
                @hasanyrole('Super Admin|Operator|Tata Usaha')
                <a href="{{ route('buku-induk.edit', $siswa->nisn) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Lengkapi Data
                </a>
                @endhasanyrole
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="border-t border-slate-100 bg-slate-50/50 px-8 flex gap-1 overflow-x-auto">
            @foreach([
                'identitas' => 'Identitas Murid',
                'orang_tua' => 'Orang Tua / Wali',
                'periodik' => 'Data Periodik',
                'jasmani' => 'Keadaan Jasmani',
                'beasiswa' => 'Beasiswa',
                'riwayat' => 'Riwayat Sekolah',
                'photo' => 'Foto Siswa',
                'akademik' => 'Prestasi Akademik',
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
    <div x-show="tab === 'beasiswa'" x-transition>
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 relative bg-slate-50/50">
                <p class="absolute -top-3 left-6 px-2 bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-full border border-slate-200">Riwayat Beasiswa</p>
                @if($siswa->beasiswa && $siswa->beasiswa->count() > 0)
                    <div class="space-y-4">
                        @foreach($siswa->beasiswa as $bs)
                        <div class="flex flex-col gap-1 border-l-4 border-emerald-400 pl-4 py-2 bg-white rounded-r-xl">
                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-black uppercase tracking-widest rounded-lg w-fit">{{ $bs->jenis_beasiswa ?? '-' }}</span>
                            <div class="flex gap-4 mt-1 text-sm">
                                <span class="text-slate-500 font-medium">Sumber: <strong class="text-slate-700">{{ $bs->sumber_beasiswa ?? '-' }}</strong></span>
                                <span class="text-slate-500 font-medium">Tahun: <strong class="text-slate-700">{{ $bs->tahun_mulai ?? '-' }} — {{ $bs->tahun_selesai ?? '-' }}</strong></span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-sm font-bold text-slate-400">Belum ada riwayat beasiswa dicatat.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ─── TAB: RIWAYAT SEKOLAH ─── --}}
    <div x-show="tab === 'riwayat'" x-transition>
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 relative bg-slate-50/50">
                <p class="absolute -top-3 left-6 px-2 bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-full border border-slate-200">Registrasi & Catatan Siswa</p>
                @if($siswa->registrasi && $siswa->registrasi->count() > 0)
                    <div class="space-y-4">
                        @foreach($siswa->registrasi as $reg)
                        <div class="flex flex-col gap-1 border-l-4 border-indigo-400 pl-4 py-2 bg-white rounded-r-xl">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 text-[9px] font-black uppercase tracking-widest rounded-lg">{{ $reg->jenis_registrasi ?? '-' }}</span>
                                @if($reg->tanggal)
                                <span class="text-[10px] text-slate-400 font-bold"><svg class="w-3 h-3 inline pb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> {{ \Carbon\Carbon::parse($reg->tanggal)->format('d F Y') }}</span>
                                @endif
                            </div>
                            <span class="text-sm font-bold text-slate-700 mt-1">{{ $reg->keterangan ?? '-' }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-sm font-bold text-slate-400">Belum ada catatan registrasi keluar, pindah, atau tamat dicatat.</p>
                    </div>
                @endif
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
                    <h3 class="text-lg font-black text-slate-800">Prestasi Belajar</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Catatan nilai, kepribadian, dan kehadiran per semester</p>
                </div>
                @hasanyrole('Super Admin|Operator|Tata Usaha')
                <a href="{{ route('buku-induk.edit', $siswa->nisn) }}" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Input Nilai via Lengkapi Data
                </a>
                @endhasanyrole
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50/80 text-slate-400 text-[0.65rem] uppercase font-extrabold tracking-widest text-center">
                        <tr>
                            <th class="px-6 py-3 text-left">Kls</th>
                            <th class="px-3 py-3">Smt</th>
                            <th class="px-3 py-3">T. Pelajaran</th>
                            @foreach($mataPelajarans as $mapel)
                            <th class="px-3 py-3 min-w-[60px]">{{ $mapel->nama }}</th>
                            @endforeach
                            <th class="px-3 py-3 border-l border-slate-100">Jml</th>
                            <th class="px-3 py-3">Rata</th>
                            <th class="px-3 py-3">Rank</th>
                            <th class="px-3 py-3 border-l border-slate-100">Sakit</th>
                            <th class="px-3 py-3">Izin</th>
                            <th class="px-3 py-3">Alpha</th>
                            <th class="px-3 py-3 border-l border-slate-100">Naik?</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach(range(1, 6) as $kelas)
                        @foreach([1, 2] as $semester)
                        @php $p = $akademikGrid[$kelas][$semester] ?? null; @endphp
                        <tr class="hover:bg-indigo-50/30 transition-colors {{ $semester == 1 ? '' : 'bg-slate-50/20' }}">
                            <td class="px-6 py-3 font-black text-slate-700">
                                @if($semester == 1)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-black">{{ $kelas }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-slate-500 font-medium text-center">{{ $semester }}</td>
                            <td class="px-3 py-3 text-slate-500 text-xs font-mono text-center">{{ $p?->tahun_pelajaran ?? '—' }}</td>
                            
                            @foreach($mataPelajarans as $mapel)
                                @php
                                    $nilai = $p ? $p->nilais->where('mata_pelajaran_id', $mapel->id)->first()?->nilai : null;
                                @endphp
                                <td class="px-3 py-3 text-center font-bold {{ ($nilai ?? 0) < 65 && $nilai !== null ? 'text-rose-600' : 'text-slate-700' }}">
                                    {{ $nilai ?? '—' }}
                                </td>
                            @endforeach
                            
                            <td class="px-3 py-3 text-center font-black text-slate-800 border-l border-slate-100">{{ $p?->jumlah_nilai ?? '—' }}</td>
                            <td class="px-3 py-3 text-center font-bold text-indigo-700">{{ $p?->rata_rata ?? '—' }}</td>
                            <td class="px-3 py-3 text-center font-black text-slate-700">{{ $p?->peringkat ?? '—' }}</td>
                            <td class="px-3 py-3 text-center text-rose-600 font-bold border-l border-slate-100">{{ $p?->hadir_sakit ?? '—' }}</td>
                            <td class="px-3 py-3 text-center text-amber-600 font-bold">{{ $p?->hadir_izin ?? '—' }}</td>
                            <td class="px-3 py-3 text-center text-slate-500 font-bold">{{ $p?->hadir_alpha ?? '—' }}</td>
                            <td class="px-3 py-3 border-l border-slate-100">
                                @if($p && $semester == 2)
                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $p->keterangan_kenaikan == 'Naik' ? 'bg-emerald-100 text-emerald-700' : ($p->keterangan_kenaikan ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-500') }}">
                                        {{ $p->keterangan_kenaikan ?? '—' }}
                                    </span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
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
@endsection
