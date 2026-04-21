@extends('layouts.app')

@section('title', 'Buku Induk Siswa')
@section('header_title', 'Buku Induk Siswa')
@section('breadcrumb', 'Buku Induk')

@section('content')
<div class="space-y-5" x-data="{ guideModal: false }">

    {{-- ── Page Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Buku Induk Siswa</h2>
            <p class="text-xs text-slate-400 mt-0.5">
                Tahun Pelajaran Aktif: 
                <strong class="text-indigo-600">{{ $tahunAktif ? $tahunAktif->tahun . ' (' . $tahunAktif->semester . ')' : 'Belum diatur' }}</strong>
            </p>
        </div>
        @php
            $totalBerkas    = collect($bukuIndukMap)->count();
            $avgKelengkapan = $totalBerkas ? round(collect($bukuIndukMap)->avg('kelengkapan')) : 0;
        @endphp
        <div class="flex gap-2 flex-wrap">
            <div class="flex items-center gap-1.5 bg-sky-50 border border-sky-100 rounded-lg px-3 py-1.5 text-xs font-bold text-sky-700">
                <span class="w-2 h-2 rounded-full bg-sky-500"></span> {{ $siswas->total() }} Siswa
            </div>
            <div class="flex items-center gap-1.5 bg-emerald-50 border border-emerald-100 rounded-lg px-3 py-1.5 text-xs font-bold text-emerald-700">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> {{ $totalBerkas }} Berkas
            </div>
            <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-100 rounded-lg px-3 py-1.5 text-xs font-bold text-amber-600">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span> Rata-rata {{ $avgKelengkapan }}%
            </div>
            <button @click="guideModal = true" class="flex items-center gap-1.5 bg-white border border-slate-200 hover:bg-slate-50 hover:text-sky-600 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-600 transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Panduan
            </button>
        </div>
    </div>

    {{-- ══ FILTER AREA ══ --}}
    <form method="GET" action="{{ route('buku-induk.index') }}" id="filter-form"
          class="flex flex-wrap gap-2 items-center">

        {{-- ① Filter Tingkat --}}
        <div class="relative shrink-0">
            <select name="tingkat"
                    onchange="this.form.submit()"
                    class="appearance-none pl-3 pr-8 py-2 text-xs font-bold border-2 rounded-xl cursor-pointer transition-all
                           bg-white text-slate-600 border-slate-200
                           hover:border-sky-400 focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10"
                    style="{{ $tingkat ? 'border-color:#0369a1;color:#0369a1;background:#e0f2fe;' : '' }}">
                <option value="">Semua Tingkat</option>
                @foreach($tingkatList as $tk)
                    <option value="{{ $tk }}" {{ $tingkat == $tk ? 'selected' : '' }}>
                        Tingkat {{ $tk }}
                    </option>
                @endforeach
            </select>
            <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 w-3 h-3 text-slate-400 pointer-events-none"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        {{-- ② Filter Kelas / Rombel --}}
        <div class="relative shrink-0">
            <select name="rombel_id"
                    onchange="this.form.submit()"
                    class="appearance-none pl-3 pr-8 py-2 text-xs font-bold border-2 rounded-xl cursor-pointer transition-all
                           bg-white text-slate-600 border-slate-200
                           hover:border-sky-400 focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10"
                    style="{{ $rombelId ? 'border-color:#0369a1;color:#0369a1;background:#e0f2fe;' : '' }}">
                <option value="">Semua Rombel</option>
                @foreach($rombelList as $rmb)
                    <option value="{{ $rmb->id }}" {{ $rombelId == $rmb->id ? 'selected' : '' }}>
                        {{ $rmb->nama }}
                    </option>
                @endforeach
            </select>
            <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 w-3 h-3 text-slate-400 pointer-events-none"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        <div class="w-px h-7 bg-slate-200 shrink-0 hidden sm:block"></div>

        {{-- ③ Search input --}}
        <div class="relative flex-1" style="min-width:180px;">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="q" id="bi-live-search"
                   value="{{ $search }}"
                   placeholder="Cari nama siswa atau NISN…"
                   class="w-full pl-9 pr-3 py-2 text-sm border-2 border-slate-200 rounded-xl
                          focus:border-sky-400 focus:outline-none focus:ring-4 focus:ring-sky-500/10
                          text-slate-700 font-medium bg-white transition-all">
        </div>

        {{-- ④ Tombol Cari --}}
        <button type="submit"
                class="px-4 py-2 text-white text-xs font-bold rounded-xl transition-all
                       whitespace-nowrap cursor-pointer hover:-translate-y-0.5 shrink-0"
                style="background:#0c4a6e;box-shadow:0 4px 10px rgba(12,74,110,.2);"
                onmouseover="this.style.background='#08334d'"
                onmouseout="this.style.background='#0c4a6e'">
            Cari
        </button>

        <div class="w-px h-7 bg-slate-200 shrink-0 hidden sm:block"></div>

        {{-- ⑤ Per-page dropdown --}}
        <div class="flex items-center gap-1.5 shrink-0">
            <span class="text-xs text-slate-500 font-semibold whitespace-nowrap hidden sm:inline">Tampilkan</span>
            <div class="relative">
                <select name="per_page" onchange="this.form.submit()"
                        class="appearance-none pl-3 pr-7 py-2 text-xs font-bold border-2 border-slate-200 rounded-xl
                               bg-white text-slate-600 cursor-pointer transition-all
                               hover:border-sky-400 focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10">
                    @foreach([10, 20, 30, 40, 50, 100] as $pp)
                        <option value="{{ $pp }}" {{ $perPage == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                    @endforeach
                </select>
                <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-3 h-3 text-slate-400 pointer-events-none"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <span class="text-xs text-slate-500 font-semibold whitespace-nowrap hidden sm:inline">/ hal.</span>
        </div>

        {{-- ⑥ Reset --}}
        @if($search || $tingkat || $rombelId || $perPage != 20)
        <a href="{{ route('buku-induk.index') }}"
           class="px-3 py-2 text-xs font-bold text-slate-400 border-2 border-slate-200 rounded-xl
                  hover:border-rose-300 hover:text-rose-500 hover:bg-rose-50 transition-all whitespace-nowrap shrink-0">
            ✕ Reset
        </a>
        @endif
    </form>

    {{-- ── Flash Message ── --}}


    {{-- ── Active Filter Badge ── --}}
    @if($tingkat || $rombelId)
    <div class="flex items-center gap-2 flex-wrap">
        <span class="text-xs text-slate-500 font-medium">Filter aktif:</span>
        @if($tingkat)
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold"
              style="background:#e0f2fe;color:#0369a1;border:1.5px solid #bae6fd;">
            Tingkat {{ $tingkat }}
        </span>
        @endif
        @if($rombelId)
        @php $selectedRombel = $rombelList->firstWhere('id', $rombelId); @endphp
        @if($selectedRombel)
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold"
              style="background:#f0fdf4;color:#166534;border:1.5px solid #bbf7d0;">
            Rombel {{ $selectedRombel->nama }}
        </span>
        @endif
        @endif
    </div>
    @endif

    {{-- ── Data Table ── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        @if($siswas->isEmpty())
        <div class="py-20 text-center px-6">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-sky-300"
                 style="background:rgba(12,74,110,.08)">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h3 class="text-sm font-bold text-slate-700">Tidak Ada Data</h3>
            <p class="text-xs text-slate-400 mt-1">Tidak ada siswa yang cocok dengan filter saat ini.</p>
        </div>

        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="bi-table">
                <thead>
                    <tr style="background:linear-gradient(135deg, #0c4a6e 0%, #0369a1 100%);">
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest"
                            style="color:rgba(186,230,253,.7); width:42px;">#</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100
                                   cursor-pointer select-none"
                            style="white-space:nowrap" onclick="biSort(0)"
                            onmouseover="this.style.background='rgba(255,255,255,.08)'"
                            onmouseout="this.style.background=''">
                            <span class="flex items-center gap-1">Nama Siswa
                                <svg class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 8V4m0 12l-4-4m4 4l4-4"/></svg>
                            </span>
                        </th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100
                                   cursor-pointer select-none"
                            style="white-space:nowrap" onclick="biSort(1)">
                            <span class="flex items-center gap-1">NISN
                                <svg class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 8V4m0 12l-4-4m4 4l4-4"/></svg>
                            </span>
                        </th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100"
                            style="white-space:nowrap">Tingkat</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100
                                   cursor-pointer select-none"
                            style="white-space:nowrap" onclick="biSort(3)">
                            <span class="flex items-center gap-1">Kelas / Rombel
                                <svg class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 8V4m0 12l-4-4m4 4l4-4"/></svg>
                            </span>
                        </th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100
                                   cursor-pointer select-none"
                            style="white-space:nowrap" onclick="biSort(4)">
                            <span class="flex items-center gap-1">Kelengkapan Data
                                <svg class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 8V4m0 12l-4-4m4 4l4-4"/></svg>
                            </span>
                        </th>
                        <th class="py-3.5 px-4 text-center text-[0.62rem] font-bold uppercase tracking-widest text-sky-100"
                            style="white-space:nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody id="bi-tbody">
                    @foreach($siswas as $index => $siswa)
                    @php
                        $bi          = $bukuIndukMap[$siswa->nisn] ?? null;
                        $kelengkapan = $bi ? $bi->kelengkapan : 0;
                        $progressBg  = $kelengkapan >= 80
                            ? 'background:linear-gradient(90deg,#10b981,#34d399)'
                            : ($kelengkapan >= 40 ? 'background:linear-gradient(90deg,#f59e0b,#fbbf24)'
                                                  : 'background:linear-gradient(90deg,#f43f5e,#fb7185)');
                        $pctColor    = $kelengkapan >= 80 ? 'color:#059669'
                            : ($kelengkapan >= 40 ? 'color:#d97706' : 'color:#e11d48');
                        $rombelNama  = $siswa->rombel?->nama ?? $siswa->rombel_saat_ini ?? '—';
                    @endphp
                    <tr class="border-b border-slate-100 transition-colors"
                        style="border-left:3px solid transparent;"
                        onmouseover="this.style.background='#f0f9ff';this.style.borderLeftColor='#0c4a6e'"
                        onmouseout="this.style.background='';this.style.borderLeftColor='transparent'">

                        <td class="py-3 px-4 text-xs text-slate-400 font-bold">{{ $siswas->firstItem() + $index }}</td>

                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-xs flex-shrink-0"
                                     style="background:linear-gradient(135deg,#e0f2fe,#bae6fd);color:#0369a1">
                                    {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                                </div>
                                <span class="font-semibold text-slate-800 text-sm">{{ $siswa->nama }}</span>
                            </div>
                        </td>

                        <td class="py-3 px-4">
                            <span class="font-mono text-xs text-slate-500 bg-slate-50 px-2 py-0.5 rounded-md border border-slate-100">
                                {{ $siswa->nisn ?? '—' }}
                            </span>
                        </td>

                        <td class="py-3 px-4">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-black">
                                {{ $siswa->tingkat_kelas ?? '—' }}
                            </span>
                        </td>

                        <td class="py-3 px-4">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-sky-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="text-sm text-slate-700 font-medium">{{ $rombelNama }}</span>
                            </div>
                        </td>

                        <td class="py-3 px-4">
                            @if($bi)
                            <div class="flex items-center gap-2" style="min-width:140px">
                                <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full" style="width:{{ $kelengkapan }}%; {{ $progressBg }}"></div>
                                </div>
                                <span class="text-[0.7rem] font-black w-8 text-right" style="{{ $pctColor }}">{{ $kelengkapan }}%</span>
                            </div>
                            @else
                            <span class="text-[0.7rem] text-slate-400 italic">Belum ada berkas</span>
                            @endif
                        </td>

                        <td class="py-3 px-4 text-center">
                            @if($bi)
                                <div class="flex items-center justify-center gap-2 flex-wrap">
                                    <a href="{{ route('buku-induk.show', $siswa->nisn) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[0.7rem] font-bold rounded-lg
                                              border transition-all hover:-translate-y-0.5"
                                       style="background:#e0f2fe;color:#0369a1;border-color:#bae6fd;"
                                       onmouseover="this.style.background='#0c4a6e';this.style.color='#fff';this.style.borderColor='#0c4a6e';this.style.boxShadow='0 4px 12px rgba(12,74,110,.3)'"
                                       onmouseout="this.style.background='#e0f2fe';this.style.color='#0369a1';this.style.borderColor='#bae6fd';this.style.boxShadow=''">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z"/>
                                        </svg>
                                        Buka
                                    </a>
                                    <a href="javascript:void(0)" onclick="confirmPrint('main', '{{ $siswa->nisn }}')"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-[0.7rem] font-bold rounded-lg shadow-lg shadow-emerald-200 transition-all hover:-translate-y-0.5">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Buku Induk
                                    </a>
                                    <a href="javascript:void(0)" onclick="confirmPrint('prestasi', '{{ $siswa->nisn }}')"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-[0.7rem] font-bold rounded-lg shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Prestasi
                                    </a>
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 text-slate-400
                                             text-[0.7rem] font-bold rounded-lg border border-slate-100 cursor-not-allowed">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636"/>
                                    </svg>
                                    No NISN
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer: row count + pagination --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3
                    px-5 py-3 bg-slate-50/80 border-t border-slate-100">
            <p class="text-xs text-slate-400">
                Menampilkan <strong class="text-slate-600">{{ $siswas->firstItem() }}</strong>–<strong class="text-slate-600">{{ $siswas->lastItem() }}</strong>
                dari <strong class="text-slate-600">{{ $siswas->total() }}</strong> siswa
            </p>
            @if($siswas->hasPages())
            <div>
                {{ $siswas->links() }}
            </div>
            @endif
        </div>
        @endif

    </div>{{-- .table card --}}

    <!-- Guide Modal -->
    <div x-show="guideModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        
        <div @click.away="guideModal = false" 
             class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all border border-white/20">
            
            <div class="bg-gradient-to-r from-sky-600 to-blue-600 px-6 py-6 text-white relative">
                <button @click="guideModal = false" class="absolute top-4 right-4 p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold tracking-tight">Panduan Buku Induk</h3>
                        <p class="text-sky-100 text-sm mt-0.5 font-medium">Informasi & Penjelasan Halaman Buku Induk Siswa</p>
                    </div>
                </div>
            </div>

            <div class="p-8 max-h-[70vh] overflow-y-auto">
                <div class="space-y-6 text-slate-600 text-sm leading-relaxed">
                    
                    <div>
                        <h4 class="text-slate-800 font-bold text-base mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">1</span>
                            Data Berdasarkan Tahun Pelajaran Aktif
                        </h4>
                        <p class="ml-8">Halaman ini menampilkan daftar siswa berdasarkan <strong>Tahun Pelajaran yang sedang aktif</strong>. Data yang muncul hanyalah siswa yang terdaftar pada sesi akademik yang sedang berjalan saat ini. Informasi tahun pelajaran aktif tercantum pada bagian atas halaman.</p>
                    </div>

                    <div>
                        <h4 class="text-slate-800 font-bold text-base mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">2</span>
                            Monitoring Kelengkapan Berkas
                        </h4>
                        <p class="ml-8">Setiap baris siswa menampilkan indikator persentase (progress bar). <span class="text-emerald-600 font-bold">Hijau</span> berarti data sangat lengkap (≥80%), <span class="text-amber-600 font-bold">Oranye</span> berarti cukup (≥40%), dan <span class="text-rose-600 font-bold">Merah</span> berarti masih banyak data yang belum dilengkapi.</p>
                    </div>

                    <div>
                        <h4 class="text-slate-800 font-bold text-base mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center text-xs">3</span>
                            Melihat & Melengkapi Data
                        </h4>
                        <p class="ml-8">Klik tombol <span class="font-bold text-sky-600">Buka</span> untuk masuk ke halaman detail Buku Induk siswa. Di halaman tersebut Anda dapat melihat semua informasi secara lengkap, dan melengkapi data melalui tombol <strong>"Lengkapi Data"</strong> yang berisi tab-tab: Identitas, Orang Tua, Periodik, Pendidikan Sebelumnya, Jasmani, Beasiswa, Registrasi, Foto, dan Prestasi Akademik.</p>
                    </div>

                    <div>
                        <h4 class="text-slate-800 font-bold text-base mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xs">4</span>
                            Filter Tingkat & Rombel
                        </h4>
                        <p class="ml-8">Gunakan filter <strong>Tingkat</strong> untuk menyaring siswa berdasarkan tingkat kelas, dan filter <strong>Rombel</strong> untuk menyaring berdasarkan kelas/rombongan belajar. Kedua filter ini bekerja bersamaan dan langsung memperbarui tabel secara otomatis.</p>
                    </div>

                </div>
            </div>

            <div class="px-8 py-5 border-t border-slate-100 bg-slate-50 flex justify-end">
                <button @click="guideModal = false" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-xl shadow-lg shadow-slate-200 transition-all hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmPrint(type = 'main', nisn) {
    let printUrl = type === 'main' 
        ? "{{ route('buku-induk.print', '__NISN__') }}".replace('__NISN__', nisn) 
        : "{{ route('buku-induk.print-prestasi', '__NISN__') }}".replace('__NISN__', nisn);
    
    let title = type === 'main' ? 'Cetak Buku Induk' : 'Cetak Prestasi Belajar';
    window.open(printUrl, "_blank");
}

(function () {
    // Live search (client-side, instant)
    const liveSearch = document.getElementById('bi-live-search');
    const tbody      = document.getElementById('bi-tbody');
    if (liveSearch && tbody) {
        let t;
        liveSearch.addEventListener('input', function () {
            clearTimeout(t);
            t = setTimeout(() => {
                const q = this.value.toLowerCase().trim();
                Array.from(tbody.querySelectorAll('tr')).forEach(row => {
                    row.style.display = (!q || row.innerText.toLowerCase().includes(q)) ? '' : 'none';
                });
            }, 180);
        });
    }

    // Column sort
    let _col = -1, _asc = true;
    window.biSort = function (colIndex) {
        if (!tbody) return;
        _asc = (_col === colIndex) ? !_asc : true;
        _col = colIndex;
        const rows = Array.from(tbody.querySelectorAll('tr'));
        rows.sort((a, b) => {
            const tA = a.querySelectorAll('td')[colIndex + 1]?.innerText.trim() ?? '';
            const tB = b.querySelectorAll('td')[colIndex + 1]?.innerText.trim() ?? '';
            const nA = parseFloat(tA), nB = parseFloat(tB);
            if (!isNaN(nA) && !isNaN(nB)) return _asc ? nA - nB : nB - nA;
            return _asc ? tA.localeCompare(tB, 'id') : tB.localeCompare(tA, 'id');
        });
        rows.forEach(r => tbody.appendChild(r));
    };
})();
</script>
@endsection
