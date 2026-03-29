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
                            No. Induk: <span class="font-bold text-slate-700">{{ $bukuInduk->no_induk ?? '—' }}</span>
                        </span>
                        <span class="flex items-center gap-1.5 text-slate-500 font-medium">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                            Kelas: <span class="font-bold text-slate-700">{{ $siswa->rombel_saat_ini ?? '-' }}</span>
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
                'orang_tua' => ['label' => 'Orang Tua / Wali', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                'akademik' => ['label' => 'Prestasi Akademik', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                'riwayat' => ['label' => 'Riwayat Sekolah', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
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
                            'Nama Lengkap' => $siswa->nama,
                            'Nama Panggilan' => $bukuInduk->nama_panggilan,
                            'Jenis Kelamin' => $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan',
                            'Tempat, Tgl Lahir' => ($siswa->tempat_lahir ?? '-') . ', ' . ($siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') : '-'),
                            'Agama' => $siswa->agama,
                            'Kewarganegaraan' => $bukuInduk->kewarganegaraan ?? $siswa->kewarganegaraan,
                            'Anak ke' => $siswa->anak_ke_berapa,
                            'Jml Saudara Kandung' => $siswa->jml_saudara_kandung,
                            'Jml Saudara Tiri' => $bukuInduk->jml_saudara_tiri,
                            'Jml Saudara Angkat' => $bukuInduk->jml_saudara_angkat,
                            'Bahasa Sehari-hari' => $bukuInduk->bahasa_sehari_hari,
                            'Golongan Darah' => $bukuInduk->golongan_darah,
                            'Bertempat Tinggal Dengan' => $bukuInduk->bertempat_tinggal_dengan,
                            'Jarak ke Sekolah' => $siswa->jarak_rumah_ke_sekolah_km ? $siswa->jarak_rumah_ke_sekolah_km . ' km' : null,
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
                        <p class="font-bold text-slate-700">{{ $siswa->alamat ?? '—' }}, RT {{ $siswa->rt }}/RW {{ $siswa->rw }}, {{ $siswa->kelurahan }}, {{ $siswa->kecamatan }}, Kode Pos {{ $siswa->kode_pos }}</p>
                    </div>

                    @if($bukuInduk->riwayat_penyakit)
                    <div class="md:col-span-2 space-y-1">
                        <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest">Riwayat Penyakit</p>
                        <p class="font-bold text-slate-700">{{ $bukuInduk->riwayat_penyakit }}</p>
                    </div>
                    @endif
                </div>
            </div>
            {{-- Right: Physical Stats --}}
            <div class="space-y-6">
                <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl p-6 text-white shadow-xl shadow-indigo-200">
                    <p class="text-xs font-black uppercase tracking-widest opacity-70 mb-5">Data Fisik Saat Masuk</p>
                    <div class="grid grid-cols-2 gap-5">
                        <div><p class="text-[0.65rem] opacity-70 mb-1">Berat Badan</p><p class="text-2xl font-black">{{ $siswa->berat_badan ?? '—' }}<span class="text-sm font-medium opacity-60"> kg</span></p></div>
                        <div><p class="text-[0.65rem] opacity-70 mb-1">Tinggi Badan</p><p class="text-2xl font-black">{{ $siswa->tinggi_badan ?? '—' }}<span class="text-sm font-medium opacity-60"> cm</span></p></div>
                        <div><p class="text-[0.65rem] opacity-70 mb-1">Lingkar Kepala</p><p class="text-xl font-black">{{ $siswa->lingkar_kepala ?? '—' }}<span class="text-xs font-medium opacity-60"> cm</span></p></div>
                    </div>
                </div>
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                    <p class="text-xs font-black text-slate-700 uppercase tracking-widest mb-4">Beasiswa</p>
                    <p class="text-sm text-slate-600 font-medium">{{ $bukuInduk->beasiswa ?? 'Tidak ada catatan beasiswa.' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB: ORANG TUA / WALI --}}
    <div x-show="tab === 'orang_tua'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @php
                $parentSections = [
                    'Ayah' => [
                        'Nama Ayah' => $siswa->nama_ayah,
                        'Tempat, Tgl Lahir' => ($bukuInduk->tempat_lahir_ayah ?? '-') . ', ' . ($bukuInduk->tanggal_lahir_ayah ? $bukuInduk->tanggal_lahir_ayah->format('d F Y') : ($siswa->tahun_lahir_ayah ?? '-')),
                        'Agama' => $bukuInduk->agama_ayah,
                        'Kewarganegaraan' => $bukuInduk->kewarganegaraan_ayah ?? 'WNI',
                        'Pendidikan' => $siswa->jenjang_pendidikan_ayah,
                        'Pekerjaan' => $siswa->pekerjaan_ayah,
                        'Penghasilan / th' => $siswa->penghasilan_ayah,
                        'Alamat' => $bukuInduk->alamat_ayah,
                        'NIK Ayah' => $siswa->nik_ayah,
                    ],
                    'Ibu' => [
                        'Nama Ibu' => $siswa->nama_ibu,
                        'Tempat, Tgl Lahir' => ($bukuInduk->tempat_lahir_ibu ?? '-') . ', ' . ($bukuInduk->tanggal_lahir_ibu ? $bukuInduk->tanggal_lahir_ibu->format('d F Y') : ($siswa->tahun_lahir_ibu ?? '-')),
                        'Agama' => $bukuInduk->agama_ibu,
                        'Kewarganegaraan' => $bukuInduk->kewarganegaraan_ibu ?? 'WNI',
                        'Pendidikan' => $siswa->jenjang_pendidikan_ibu,
                        'Pekerjaan' => $siswa->pekerjaan_ibu,
                        'Penghasilan / th' => $siswa->penghasilan_ibu,
                        'Alamat' => $bukuInduk->alamat_ibu,
                        'NIK Ibu' => $siswa->nik_ibu,
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
                        $waliFields = [
                            'Nama Wali' => $bukuInduk->nama_wali_bi ?? $siswa->nama_wali,
                            'Hubungan dengan Siswa' => $bukuInduk->hubungan_wali,
                            'Pendidikan' => $bukuInduk->pendidikan_wali_bi ?? $siswa->jenjang_pendidikan_wali,
                            'Pekerjaan' => $bukuInduk->pekerjaan_wali_bi ?? $siswa->pekerjaan_wali,
                            'Alamat' => $bukuInduk->alamat_wali_bi,
                            'No. Telepon' => $bukuInduk->telp_wali_bi,
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
                <button x-data x-on:click="$dispatch('open-prestasi-modal')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah / Update Nilai
                </button>
                @endhasanyrole
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50/80 text-slate-400 text-[0.65rem] uppercase font-extrabold tracking-widest">
                        <tr>
                            <th class="px-6 py-3">Kls</th>
                            <th class="px-3 py-3">Smt</th>
                            <th class="px-3 py-3">T.Pelajaran</th>
                            <th class="px-3 py-3">Agama</th>
                            <th class="px-3 py-3">PKn</th>
                            <th class="px-3 py-3">B.Ind</th>
                            <th class="px-3 py-3">MTK</th>
                            <th class="px-3 py-3">IPA</th>
                            <th class="px-3 py-3">IPS</th>
                            <th class="px-3 py-3">SBK</th>
                            <th class="px-3 py-3">PJOK</th>
                            <th class="px-3 py-3">Mulok</th>
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
                            <td class="px-3 py-3 text-slate-500 font-medium">{{ $semester }}</td>
                            <td class="px-3 py-3 text-slate-500 text-xs font-mono">{{ $p?->tahun_pelajaran ?? '—' }}</td>
                            @foreach(['nilai_agama', 'nilai_pkn', 'nilai_bindo', 'nilai_mtk', 'nilai_ipa', 'nilai_ips', 'nilai_sbk', 'nilai_pjok', 'nilai_mulok'] as $field)
                            <td class="px-3 py-3 text-center font-bold {{ ($p?->$field ?? 0) < 65 && $p?->$field !== null ? 'text-rose-600' : 'text-slate-700' }}">
                                {{ $p?->$field ?? '—' }}
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

    {{-- TAB: RIWAYAT SEKOLAH --}}
    <div x-show="tab === 'riwayat'" x-transition>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-sky-500 rounded-full"></span>Perkembangan Murid
                </h3>
                <div class="space-y-4">
                    @foreach([
                        'Asal Masuk Sekolah' => $bukuInduk->asal_masuk_sekolah,
                        'Nama TK / Paud Asal' => $bukuInduk->nama_tk_asal,
                        'Sekolah Asal (Dapodik)' => $siswa->sekolah_asal,
                        'Tanggal Masuk Sekolah' => $bukuInduk->tgl_masuk_sekolah?->format('d F Y'),
                        'Pindahan dari Sekolah' => $bukuInduk->pindah_dari,
                        'Masuk di Kelas' => $bukuInduk->kelas_pindah_masuk,
                        'Tanggal Pindah Masuk' => $bukuInduk->tgl_pindah_masuk?->format('d F Y'),
                    ] as $label => $value)
                    <div class="flex justify-between items-start py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-400">{{ $label }}</span>
                        <span class="text-sm font-bold text-slate-700 text-right max-w-[60%]">{{ $value ?? '—' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-emerald-500 rounded-full"></span>Tamat Belajar / Lulus
                    </h3>
                    <div class="space-y-4">
                        @foreach([
                            'Tanggal Lulus' => $bukuInduk->tgl_lulus?->format('d F Y'),
                            'Nomor Ijazah' => $bukuInduk->no_ijazah ?? $siswa->no_seri_ijazah,
                            'No. Peserta UN' => $siswa->no_peserta_un,
                            'Melanjutkan ke Sekolah' => $bukuInduk->lanjut_ke,
                        ] as $label => $value)
                        <div class="flex justify-between items-start py-2 border-b border-slate-50">
                            <span class="text-sm font-bold text-slate-400">{{ $label }}</span>
                            <span class="text-sm font-bold text-slate-700 text-right max-w-[60%]">{{ $value ?? '—' }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-rose-500 rounded-full"></span>Meninggalkan Sekolah
                    </h3>
                    <div class="space-y-4">
                        @foreach([
                            'Tanggal Keluar' => $bukuInduk->tgl_keluar?->format('d F Y'),
                            'Alasan Keluar' => $bukuInduk->alasan_keluar,
                        ] as $label => $value)
                        <div class="flex justify-between items-start py-2 border-b border-slate-50">
                            <span class="text-sm font-bold text-slate-400">{{ $label }}</span>
                            <span class="text-sm font-bold text-slate-700 text-right max-w-[60%]">{{ $value ?? '—' }}</span>
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
        <div @click.away="open = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
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
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        @foreach(\App\Models\PrestasiBelajar::subjectFields() as $field => $label)
                        <div class="space-y-1">
                            <label class="text-[0.65rem] font-bold text-slate-400">{{ $label }}</label>
                            <input type="number" name="{{ $field }}" min="0" max="100" step="0.5" placeholder="—"
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

</div>
@endsection
