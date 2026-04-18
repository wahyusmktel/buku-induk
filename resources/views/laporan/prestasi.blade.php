@extends('layouts.app')

@section('title', 'Laporan Prestasi Belajar')
@section('header_title', 'Laporan Prestasi Belajar')
@section('breadcrumb', 'Laporan Prestasi Belajar')

@section('content')

{{-- ── Hero Header ─────────────────────────────────────────────────────────── --}}
<div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl shadow-xl p-8 mb-8 relative overflow-hidden text-white">
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div>
            <h2 class="text-3xl font-extrabold mb-2 tracking-tight">Laporan Prestasi Belajar</h2>
            <p class="text-indigo-100 font-medium max-w-xl">
                Ringkasan nilai, peringkat, dan kehadiran siswa per rombel
                @if($tahunDipilih)
                    untuk <span class="font-bold text-white">{{ $tahunDipilih->tahun }} – Semester {{ $tahunDipilih->semester }}</span>.
                @else
                    (pilih tahun pelajaran).
                @endif
            </p>
        </div>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('laporan.prestasi') }}" class="shrink-0">
            <div class="flex items-center gap-2">
                <div class="relative">
                    <select name="tahun_id"
                            onchange="this.form.submit()"
                            class="bg-white/20 backdrop-blur-sm border border-white/30 text-white rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold appearance-none focus:outline-none focus:ring-2 focus:ring-white/50 cursor-pointer min-w-[200px]">
                        <option value="" class="text-slate-800">-- Semua Tahun --</option>
                        @foreach($tahunPelajarans as $tp)
                            <option value="{{ $tp->id }}"
                                    class="text-slate-800"
                                    {{ $tahunDipilih?->id === $tp->id ? 'selected' : '' }}>
                                {{ $tp->tahun }} – Semester {{ $tp->semester }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="w-4 h-4 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-10 top-10 w-20 h-20 bg-indigo-400/20 rounded-full blur-2xl animate-pulse pointer-events-none"></div>
</div>

@if(!$tahunDipilih)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center">
        <p class="text-amber-700 font-semibold">Pilih tahun pelajaran untuk menampilkan data prestasi.</p>
    </div>
@elseif($rombelData->isEmpty())
    <div class="bg-white border border-slate-100 rounded-2xl p-10 text-center shadow-sm">
        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <p class="text-slate-500 font-semibold">Tidak ada rombel pada tahun pelajaran ini.</p>
    </div>
@else

{{-- ── Rombel Cards ─────────────────────────────────────────────────────────── --}}
@foreach($rombelData as $item)
@php
    $rombel    = $item['rombel'];
    $siswaData = $item['siswa_data'];
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6" x-data="{ open: true }">
    {{-- Rombel Header --}}
    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-violet-50 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-indigo-500 rounded-xl flex items-center justify-center text-white font-extrabold text-sm shrink-0">
                {{ $rombel->tingkat }}
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">{{ $rombel->nama }}</h3>
                <p class="text-xs text-slate-500 font-medium">
                    {{ $rombel->jumlah_siswa }} siswa
                    @if($rombel->nama_wali_kelas)
                        · Wali Kelas: {{ $rombel->nama_wali_kelas }}
                    @endif
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('cetak.leger', $rombel->id) }}?preview=1"
               target="_blank"
               class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-colors shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Leger
            </a>
            <button @click="open = !open"
                    class="flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl transition-colors">
                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                <span x-text="open ? 'Sembunyikan' : 'Tampilkan'">Sembunyikan</span>
            </button>
        </div>
    </div>

    {{-- Siswa Table --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        @if($siswaData->isEmpty())
            <div class="p-6 text-center">
                <p class="text-sm text-slate-400 font-medium">Tidak ada siswa di rombel ini.</p>
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-8">No</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">NISN</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Smt</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Peringkat</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-amber-500 uppercase tracking-wider">Sakit</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-orange-500 uppercase tracking-wider">Izin</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-rose-500 uppercase tracking-wider">Alpha</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($siswaData as $idx => $sw)
                    @php $siswa = $sw['siswa']; $prestasiList = $sw['prestasi']; @endphp

                    @if($prestasiList->isEmpty())
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-3 text-slate-400 text-xs">{{ $idx + 1 }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-700">{{ $siswa->nama }}</td>
                        <td class="px-4 py-3 text-slate-400 font-mono text-xs">{{ $siswa->nisn ?? '-' }}</td>
                        <td class="px-4 py-3 text-center text-slate-300" colspan="7">
                            <span class="text-xs italic">Belum ada data prestasi</span>
                        </td>
                    </tr>
                    @else
                    @foreach($prestasiList as $pIdx => $prestasi)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        @if($pIdx === 0)
                        <td class="px-4 py-3 text-slate-400 text-xs" rowspan="{{ $prestasiList->count() }}">{{ $idx + 1 }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-700" rowspan="{{ $prestasiList->count() }}">{{ $siswa->nama }}</td>
                        <td class="px-4 py-3 text-slate-500 font-mono text-xs" rowspan="{{ $prestasiList->count() }}">{{ $siswa->nisn ?? '-' }}</td>
                        @endif
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold {{ $prestasi->semester == 1 ? 'bg-indigo-100 text-indigo-700' : 'bg-violet-100 text-violet-700' }}">
                                {{ $prestasi->semester }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center font-black text-indigo-700">
                            {{ $prestasi->rata_rata ? number_format($prestasi->rata_rata, 1) : '-' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($prestasi->peringkat)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-black
                                    {{ $prestasi->peringkat <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $prestasi->peringkat }}
                                </span>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-amber-600 font-semibold text-xs">{{ $prestasi->hadir_sakit ?? '-' }}</td>
                        <td class="px-4 py-3 text-center text-orange-500 font-semibold text-xs">{{ $prestasi->hadir_izin ?? '-' }}</td>
                        <td class="px-4 py-3 text-center text-rose-500 font-semibold text-xs">{{ $prestasi->hadir_alpha ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($prestasi->keterangan_kenaikan)
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-bold
                                    {{ Str::contains($prestasi->keterangan_kenaikan, ['Naik', 'naik', 'Lulus', 'lulus']) ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $prestasi->keterangan_kenaikan }}
                                </span>
                            @else
                                <span class="text-slate-300 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endforeach

@endif

@endsection
