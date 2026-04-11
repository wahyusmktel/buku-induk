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

    {{-- Sessions messages --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Hero Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="h-3 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500"></div>
        <div class="p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 text-indigo-700 flex items-center justify-center font-black text-2xl shadow-inner">
                    {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                </div>
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
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                            NIS: <span class="font-bold text-slate-700">{{ $siswa->nis ?? '-' }}</span>
                        </span>
                        <span class="flex items-center gap-1.5 text-slate-500 font-medium">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
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
                'identitas' => ['label' => 'Identitas Murid', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                'photo' => ['label' => 'Photo', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                'orang_tua' => ['label' => 'Orang Tua / Wali', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                'akademik' => ['label' => 'Prestasi Akademik', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                'riwayat' => ['label' => 'Riwayat Sekolah', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                'jejak' => ['label' => 'Jejak Rombel', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z'],
            ] as $key => $meta)
            <button @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'border-b-2 border-indigo-600 text-indigo-700 font-black' : 'text-slate-500 hover:text-slate-700'"
                    class="flex items-center gap-2 px-4 py-3.5 text-sm font-semibold transition-all whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}"/></svg>
                {{ $meta['label'] }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- TAB: IDENTITAS MURID --}}
    <div x-show="tab === 'identitas'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left: Dapodik Data --}}
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-indigo-600 rounded-full"></span>Keterangan Murid
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-12">
                    @php
                        $fields = [
                            'NIK' => $siswa->nik,
                            'Nama Lengkap' => $siswa->nama,
                            'Nama Panggilan' => $siswa->nama_panggilan,
                            'Jenis Kelamin' => ($siswa->jenis_kelamin == 'L' || $siswa->jk == 'L') ? 'Laki-laki' : 'Perempuan',
                            'Tempat, Tgl Lahir' => ($siswa->tempat_lahir ?? '-') . ', ' . ($siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') : '-'),
                            'Agama' => $siswa->agama,
                            'Kewarganegaraan' => $siswa->kewarganegaraan,
                            'Jml Saudara Kandung' => $siswa->dataPeriodik->jml_saudara_kandung ?? $siswa->jml_saudara_kandung ?? 0,
                            'Jml Saudara Tiri' => $siswa->dataPeriodik->jml_saudara_tiri ?? 0,
                            'Jml Saudara Angkat' => $siswa->dataPeriodik->jml_saudara_angkat ?? 0,
                            'Bahasa Sehari-hari' => $siswa->dataPeriodik->bahasa_sehari_hari ?? '-',
                            'Golongan Darah' => $siswa->keadaanJasmani->golongan_darah ?? '-',
                            'No. Telepon' => $siswa->nomor_telepon ?? $siswa->telepon ?? '-',
                            'Jarak ke Sekolah' => ($siswa->dataPeriodik->jarak_tempat_tinggal_ke_sekolah ?? $siswa->jarak_rumah_ke_sekolah_km ?? '-') . ' km',
                        ];
                    @endphp
                    @foreach($fields as $label => $value)
                    <div class="space-y-1">
                        <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest">{{ $label }}</p>
                        <p class="font-bold text-slate-700">{{ $value ?? '—' }}</p>
                    </div>
                    @endforeach

                    <div class="md:col-span-2 space-y-1">
                        <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest">Alamat Tempat Tinggal</p>
                        <p class="font-bold text-slate-700">{{ $siswa->dataPeriodik->alamat_tinggal ?? $siswa->alamat ?? '—' }}</p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest">Bertempat Tinggal Pada</p>
                        <p class="font-bold text-slate-700">{{ $siswa->dataPeriodik->bertempat_tinggal_pada ?? $siswa->jenis_tinggal ?? '—' }}</p>
                    </div>
                </div>
            </div>
            {{-- Right: Physical Stats --}}
            <div class="space-y-6">
                <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl p-6 text-white shadow-xl shadow-indigo-200">
                    <p class="text-xs font-black uppercase tracking-widest opacity-70 mb-5">Keadaan Jasmani Saat Masuk</p>
                    <div class="grid grid-cols-2 gap-5">
                        <div><p class="text-[0.65rem] opacity-70 mb-1">Berat Badan</p><p class="text-2xl font-black">{{ $siswa->keadaanJasmani->berat_badan ?? '-' }}<span class="text-sm font-medium opacity-60"> kg</span></p></div>
                        <div><p class="text-[0.65rem] opacity-70 mb-1">Tinggi Badan</p><p class="text-2xl font-black">{{ $siswa->keadaanJasmani->tinggi_badan ?? '-' }}<span class="text-sm font-medium opacity-60"> cm</span></p></div>
                    </div>
                </div>
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-3">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Riwayat Penyakit Khusus</p>
                    <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ $siswa->keadaanJasmani->nama_riwayat_penyakit ?? 'Tidak ada riwayat penyakit signifikan' }}</p>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest pt-2">Kelainan Jasmani / Berkebutuhan Khusus</p>
                    <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ $siswa->keadaanJasmani->kelainan_jasmani ?? 'Tidak ada' }}</p>
                </div>
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 overflow-hidden">
                    <p class="text-xs font-black text-slate-700 uppercase tracking-widest mb-4">Riwayat Beasiswa</p>
                    @if($siswa->beasiswa && $siswa->beasiswa->count() > 0)
                        <div class="space-y-3">
                            @foreach($siswa->beasiswa as $beasiswa)
                            <div class="flex flex-col gap-1 border-l-4 border-emerald-400 pl-3">
                                <span class="text-[9px] font-black text-slate-400 uppercase">{{ $beasiswa->tahun ?? '-' }}</span>
                                <span class="text-sm font-bold text-slate-700">{{ $beasiswa->jenis_beasiswa ?? '-' }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-600 font-medium">Tidak ada riwayat beasiswa dicatat.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- TAB: PHOTO SISWA --}}
    <div x-show="tab === 'photo'" x-transition x-data="{ 
        preview1: '{{ $bukuInduk->foto_1 ? Storage::url($bukuInduk->foto_1) : '' }}',
        preview2: '{{ $bukuInduk->foto_2 ? Storage::url($bukuInduk->foto_2) : '' }}'
    }">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                <span class="w-1.5 h-5 bg-indigo-600 rounded-full"></span>Unggah Pas Photo Murid
            </h3>
            
            <form action="{{ route('buku-induk.update', $bukuInduk->nisn) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Photo 1 --}}
                    <div class="space-y-4">
                        <label class="block text-sm font-bold text-slate-700">Pas Photo 1 (Wajib Buku Induk)</label>
                        <div class="relative group">
                            <div class="w-48 h-64 mx-auto rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden transition-all group-hover:border-indigo-300">
                                <template x-if="preview1">
                                    <img :src="preview1" class="w-full h-full object-cover">
                                </template>
                                <div x-show="!preview1" class="text-center p-4">
                                    <svg class="w-10 h-10 text-slate-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Belum ada foto</p>
                                </div>
                            </div>
                        </div>
                        <input type="file" name="foto_1" accept="image/*" 
                               @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { preview1 = e.target.result; }; reader.readAsDataURL(file); }"
                               class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all">
                    </div>

                    {{-- Photo 2 --}}
                    <div class="space-y-4">
                        <label class="block text-sm font-bold text-slate-700">Pas Photo 2 (Arsip Sekolah)</label>
                        <div class="relative group">
                            <div class="w-48 h-64 mx-auto rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden transition-all group-hover:border-indigo-300">
                                <template x-if="preview2">
                                    <img :src="preview2" class="w-full h-full object-cover">
                                </template>
                                <div x-show="!preview2" class="text-center p-4">
                                    <svg class="w-10 h-10 text-slate-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Belum ada foto</p>
                                </div>
                            </div>
                        </div>
                        <input type="file" name="foto_2" accept="image/*" 
                               @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { preview2 = e.target.result; }; reader.readAsDataURL(file); }"
                               class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all">
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-2xl font-black text-sm shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all active:scale-95">
                        Simpan Perubahan Foto
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TAB: ORANG TUA / WALI --}}
    <div x-show="tab === 'orang_tua'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @php
                $ayah = $siswa->dataOrangTua ? $siswa->dataOrangTua->where('jenis', 'Ayah')->first() : null;
                $ibu = $siswa->dataOrangTua ? $siswa->dataOrangTua->where('jenis', 'Ibu')->first() : null;
                $parentSections = [
                    'Ayah' => [
                        'Nama Ayah' => $ayah->nama ?? '-',
                        'Pendidikan' => $ayah->pendidikan_terakhir ?? '-',
                        'Pekerjaan' => $ayah->pekerjaan ?? '-',
                    ],
                    'Ibu' => [
                        'Nama Ibu' => $ibu->nama ?? '-',
                        'Pendidikan' => $ibu->pendidikan_terakhir ?? '-',
                        'Pekerjaan' => $ibu->pekerjaan ?? '-',
                    ],
                ];
            @endphp
            @foreach($parentSections as $parent => $fields)
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-5 {{ $parent === 'Ayah' ? 'bg-blue-500' : 'bg-pink-500' }} rounded-full"></span>
                    Data {{ $parent }} Kandung
                </h3>
                <div class="space-y-4">
                    @foreach($fields as $label => $value)
                    <div class="space-y-0.5">
                        <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest">{{ $label }}</p>
                        <p class="font-bold text-slate-700 text-sm">{{ $value ?? '—' }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-amber-500 rounded-full"></span>Data Wali
                </h3>
                <div class="space-y-4">
                    @php
                        $wali = $siswa->dataOrangTua ? $siswa->dataOrangTua->where('jenis', 'Wali')->first() : null;
                        $waliFields = [
                            'Nama Wali' => $wali->nama ?? '-',
                            'Hubungan dengan Siswa' => $wali->status_hubungan_wali ?? '-',
                            'Pendidikan' => $wali->pendidikan_terakhir ?? '-',
                            'Pekerjaan' => $wali->pekerjaan ?? '-',
                        ];
                    @endphp
                    @foreach($waliFields as $label => $value)
                    <div class="space-y-0.5">
                        <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest">{{ $label }}</p>
                        <p class="font-bold text-slate-700 text-sm">{{ $value ?? '—' }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- TAB: PRESTASI AKADEMIK --}}
    <div x-show="tab === 'akademik'" x-transition>
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-slate-800">Prestasi Belajar</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Catatan nilai, kepribadian, dan kehadiran per semester</p>
                </div>
                @hasanyrole('Super Admin|Operator|Tata Usaha')
                <div class="flex gap-2">
                    <button x-data x-on:click="$dispatch('open-import-prestasi-modal')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-bold rounded-xl shadow-sm transition-all cursor-pointer">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Import Excel
                    </button>
                    <button x-data x-on:click="$dispatch('open-prestasi-modal')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah / Update Nilai
                    </button>
                </div>
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

    {{-- TAB: RIWAYAT SEKOLAH / REGISTRASI --}}
    <div x-show="tab === 'riwayat'" x-transition>
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                <span class="w-1.5 h-5 bg-indigo-500 rounded-full"></span>Registrasi & Catatan Siswa
            </h3>
            <div class="space-y-6">
                @if($siswa->registrasi && $siswa->registrasi->count() > 0)
                    @foreach($siswa->registrasi as $reg)
                    <div class="flex flex-col gap-1 border-l-4 border-indigo-400 pl-4 py-2 bg-indigo-50/10 rounded-r-xl">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 text-[9px] font-black uppercase tracking-widest rounded-lg">{{ $reg->jenis_registrasi ?? '-' }}</span>
                            @if($reg->tanggal)
                            <span class="text-[10px] text-slate-400 font-bold"><svg class="w-3 h-3 inline pb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> {{ \Carbon\Carbon::parse($reg->tanggal)->format('d F Y') }}</span>
                            @endif
                        </div>
                        <span class="text-sm font-bold text-slate-700 mt-1">{{ $reg->keterangan ?? '-' }}</span>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-6">
                        <p class="text-sm font-bold text-slate-400">Belum ada catatan registrasi keluar, pindah, atau tamat dicatat.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- TAB: JEJAK ROMBEL --}}
    <div x-show="tab === 'jejak'" x-transition>
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-800">Jejak Rombel & Tahun Pelajaran</h3>
                <p class="text-sm text-slate-500 mt-0.5">Riwayat penempatan kelas siswa di setiap sesi akademik aktif</p>
            </div>
            <div class="p-8">
                <div class="relative">
                    {{-- Vertical Line --}}
                    <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-slate-100"></div>

                    <div class="space-y-8">
                        @foreach($bukuInduk->riwayatSiswa() as $history)
                        <div class="relative pl-12">
                            {{-- Dot --}}
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

    {{-- MODAL: Input Prestasi --}}
    @hasanyrole('Super Admin|Operator|Tata Usaha')
    <div x-data="{ open: false }" @open-prestasi-modal.window="open = true"
         x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 text-white rounded-t-3xl flex items-center justify-between">
                <h3 class="text-xl font-extrabold tracking-tight">Input / Update Nilai Semester</h3>
                <button @click="open = false" class="p-2 hover:bg-white/10 rounded-full cursor-pointer"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form action="{{ route('prestasi.store', $siswa->nisn) }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Kelas</label>
                        <select name="kelas" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                            @foreach(range(1,6) as $k)<option value="{{ $k }}">Kelas {{ $k }}</option>@endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Semester</label>
                        <select name="semester" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                            <option value="1">Ganjil (1)</option>
                            <option value="2">Genap (2)</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Tahun Pelajaran</label>
                        <input type="text" name="tahun_pelajaran" placeholder="2024/2025" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                    </div>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3">Nilai Mata Pelajaran</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
                        @foreach($mataPelajarans as $mapel)
                        <div class="space-y-1">
                            <label class="text-[0.65rem] font-bold text-slate-400">{{ $mapel->nama }}</label>
                            <input type="number" name="nilai[{{ $mapel->id }}]" min="0" max="100" step="0.5" placeholder="—"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700 text-sm">
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Peringkat</label><input type="number" name="peringkat" min="1" placeholder="—" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"></div>
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Sakit (hari)</label><input type="number" name="hadir_sakit" min="0" value="0" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"></div>
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Izin (hari)</label><input type="number" name="hadir_izin" min="0" value="0" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"></div>
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Alpha (hari)</label><input type="number" name="hadir_alpha" min="0" value="0" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Sikap</label>
                        <select name="sikap" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"><option value="">—</option><option>Baik</option><option>Cukup</option><option>Kurang</option></select></div>
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Kerajinan</label>
                        <select name="kerajinan" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"><option value="">—</option><option>Baik</option><option>Cukup</option><option>Kurang</option></select></div>
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Kebersihan</label>
                        <select name="kebersihan_kerapian" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"><option value="">—</option><option>Baik</option><option>Cukup</option><option>Kurang</option></select></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Kenaikan Kelas</label>
                        <select name="keterangan_kenaikan" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"><option value="">—</option><option>Naik</option><option>Tidak Naik</option></select></div>
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Tgl Keputusan</label>
                        <input type="date" name="tgl_keputusan_kenaikan" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"></div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="open = false" class="flex-1 py-3 text-sm font-bold text-slate-500 hover:bg-slate-100 rounded-2xl transition-all cursor-pointer">Batal</button>
                    <button type="submit" class="flex-[2] py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-lg transition-all cursor-pointer">Simpan Nilai</button>
                </div>
            </form>
        </div>
    </div>
    @endhasanyrole

    {{-- MODAL: Import Prestasi --}}
    @hasanyrole('Super Admin|Operator|Tata Usaha')
    <div x-data="{ open: false }" @open-import-prestasi-modal.window="open = true"
         x-show="open" x-transition class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden">
            <div class="bg-emerald-600 px-8 py-6 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/10 rounded-xl">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold tracking-tight">Import Nilai Excel</h3>
                        <p class="text-xs text-white/70 font-bold uppercase tracking-widest">Update massal nilai semester</p>
                    </div>
                </div>
                <button @click="open = false" class="p-2 hover:bg-white/10 rounded-full cursor-pointer"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            
            <div class="p-8 space-y-6">
                {{-- Download Template Guide --}}
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 flex items-start gap-4">
                    <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm font-bold text-emerald-900">Gunakan Template Resmi</p>
                        <p class="text-xs text-emerald-700 leading-relaxed font-medium">Pastikan format kolom sesuai dengan template agar data berhasil diimpor. Nilai yang sudah ada akan diperbarui.</p>
                        <a href="{{ route('prestasi.template') }}" class="inline-flex items-center gap-1.5 text-emerald-600 text-xs font-black hover:text-emerald-750 transition-colors mt-2">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Unduh Template Format Excel
                        </a>
                    </div>
                </div>

                <form action="{{ route('prestasi.import', $siswa->nisn) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest px-1">Pilih File Excel</label>
                        <div class="relative group">
                            <input type="file" name="file" required accept=".xlsx,.xls,.csv"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 font-bold text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 cursor-pointer">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="open = false" class="flex-1 py-3.5 text-sm font-bold text-slate-500 hover:bg-slate-100 rounded-2xl transition-all cursor-pointer">Batal</button>
                        <button type="submit" class="flex-[2] py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-emerald-200 transition-all cursor-pointer">Unggah & Proses Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endhasanyrole

</div>
@endsection
