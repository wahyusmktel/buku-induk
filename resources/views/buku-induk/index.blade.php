@extends('layouts.app')

@section('title', 'Buku Induk Siswa')
@section('header_title', 'Buku Induk Siswa')
@section('breadcrumb', 'Buku Induk')

@section('content')
<div class="space-y-5">

    {{-- ── Page Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Buku Induk Siswa</h2>
            <p class="text-xs text-slate-400 mt-0.5">Arsip digital permanen — tersedia untuk seluruh siswa aktif maupun alumni.</p>
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
        </div>
    </div>

    {{-- ══ FILTER AREA ══ --}}
    {{-- Baris 1: Status pills sebagai <a> link (tidak konflik dengan form) --}}
    <div class="flex flex-wrap gap-2 items-center">
        @foreach(['Aktif', 'Lulus', 'Semua'] as $st)
            <a href="{{ route('buku-induk.index', array_filter([
                    'status'   => $st,
                    'q'        => $search,
                    'tahun_id' => $tahunId,
                    'per_page' => ($perPage != 20) ? $perPage : null,
                ], fn($v) => $v !== null && $v !== '')) }}"
               class="px-4 py-1.5 rounded-full text-xs font-bold border-2 transition-all
                      {{ $statusFilter == $st
                         ? 'bg-sky-700 text-white border-sky-700 shadow-md shadow-sky-200'
                         : 'bg-white text-slate-500 border-slate-200 hover:border-sky-400 hover:text-sky-700 hover:bg-sky-50' }}">
                {{ $st }}
            </a>
        @endforeach
    </div>

    {{-- Baris 2: Satu form tunggal — Tahun | Search | Cari | Per-page | Reset --}}
    <form method="GET" action="{{ route('buku-induk.index') }}" id="filter-form"
          class="flex flex-wrap gap-2 items-center">

        {{-- Status dibawa sebagai hidden (agar form search tidak reset status) --}}
        <input type="hidden" name="status" value="{{ $statusFilter }}">

        {{-- ① Tahun Pelajaran — ada di DALAM form dengan name="tahun_id" --}}
        <div class="relative shrink-0">
            <select name="tahun_id"
                    onchange="this.form.submit()"
                    class="appearance-none pl-3 pr-8 py-2 text-xs font-bold border-2 rounded-xl cursor-pointer transition-all
                           bg-white text-slate-600 border-slate-200
                           hover:border-sky-400 focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10"
                    style="{{ $tahunId ? 'border-color:#0369a1;color:#0369a1;background:#e0f2fe;' : '' }}">
                <option value="">Semua Angkatan</option>
                @foreach($tahunPelajarans as $tp)
                    <option value="{{ $tp->id }}" {{ $tahunId == $tp->id ? 'selected' : '' }}>
                        {{ $tp->tahun }} — Sem. {{ $tp->semester }}
                    </option>
                @endforeach
            </select>
            <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 w-3 h-3 text-slate-400 pointer-events-none"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        <div class="w-px h-7 bg-slate-200 shrink-0 hidden sm:block"></div>

        {{-- ② Search input --}}
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

        {{-- ③ Tombol Cari --}}
        <button type="submit"
                class="px-4 py-2 text-white text-xs font-bold rounded-xl transition-all
                       whitespace-nowrap cursor-pointer hover:-translate-y-0.5 shrink-0"
                style="background:#0c4a6e;box-shadow:0 4px 10px rgba(12,74,110,.2);"
                onmouseover="this.style.background='#08334d'"
                onmouseout="this.style.background='#0c4a6e'">
            Cari
        </button>

        <div class="w-px h-7 bg-slate-200 shrink-0 hidden sm:block"></div>

        {{-- ④ Per-page dropdown --}}
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

        {{-- ⑤ Reset --}}
        @if($search || $tahunId || $statusFilter !== 'Aktif' || $perPage != 20)
        <a href="{{ route('buku-induk.index') }}"
           class="px-3 py-2 text-xs font-bold text-slate-400 border-2 border-slate-200 rounded-xl
                  hover:border-rose-300 hover:text-rose-500 hover:bg-rose-50 transition-all whitespace-nowrap shrink-0">
            ✕ Reset
        </a>
        @endif
    </form>

    {{-- ── Flash Message ── --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <p class="text-xs font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    {{-- ── Active Filter Badge ── --}}
    @if($tahunId)
    @php $selectedTp = $tahunPelajarans->firstWhere('id', $tahunId); @endphp
    @if($selectedTp)
    <div class="flex items-center gap-2 flex-wrap">
        <span class="text-xs text-slate-500 font-medium">Filter aktif:</span>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold"
              style="background:#e0f2fe;color:#0369a1;border:1.5px solid #bae6fd;">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Angkatan {{ $selectedTp->tahun }} — Sem. {{ $selectedTp->semester }}
        </span>
    </div>
    @endif
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
                            style="white-space:nowrap" onclick="biSort(1)"
                            onmouseover="this.style.background='rgba(255,255,255,.08)'"
                            onmouseout="this.style.background=''">
                            <span class="flex items-center gap-1">NISN
                                <svg class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 8V4m0 12l-4-4m4 4l4-4"/></svg>
                            </span>
                        </th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100
                                   cursor-pointer select-none"
                            style="white-space:nowrap" onclick="biSort(2)"
                            onmouseover="this.style.background='rgba(255,255,255,.08)'"
                            onmouseout="this.style.background=''">
                            <span class="flex items-center gap-1">Kelas / Rombel Terakhir
                                <svg class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 8V4m0 12l-4-4m4 4l4-4"/></svg>
                            </span>
                        </th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100"
                            style="white-space:nowrap">Status</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100
                                   cursor-pointer select-none"
                            style="white-space:nowrap" onclick="biSort(4)"
                            onmouseover="this.style.background='rgba(255,255,255,.08)'"
                            onmouseout="this.style.background=''">
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
                        $badgeStyle  = match($siswa->status) {
                            'Aktif'         => 'background:#d1fae5;color:#065f46',
                            'Lulus'         => 'background:#e0f2fe;color:#0369a1',
                            'Keluar/Mutasi' => 'background:#ffe4e6;color:#9f1239',
                            default         => 'background:#f1f5f9;color:#475569',
                        };
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
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-sky-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="text-sm text-slate-700 font-medium">{{ $siswa->rombel_saat_ini ?? 'Tidak diketahui' }}</span>
                            </div>
                        </td>

                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.65rem] font-bold"
                                  style="{{ $badgeStyle }}">
                                {{ $siswa->status ?? 'Aktif' }}
                            </span>
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
                {{ $siswas->appends(['q' => $search, 'status' => $statusFilter, 'tahun_id' => $tahunId, 'per_page' => $perPage])->links() }}
            </div>
            @endif
        </div>
        @endif

    </div>{{-- .table card --}}

</div>

<script>
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
