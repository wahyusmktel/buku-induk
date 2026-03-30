@extends('layouts.app')

@section('title', 'Buku Induk Siswa')
@section('header_title', 'Buku Induk Siswa')
@section('breadcrumb', 'Buku Induk')

@section('content')
<div class="space-y-5">

    {{-- ── Page Header ──────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Buku Induk Siswa</h2>
            <p class="text-xs text-slate-400 mt-0.5">Arsip digital permanen — tersedia untuk seluruh siswa aktif maupun alumni.</p>
        </div>

        {{-- Stats chips --}}
        @php
            $totalBerkas     = collect($bukuIndukMap)->count();
            $avgKelengkapan  = $totalBerkas ? round(collect($bukuIndukMap)->avg('kelengkapan')) : 0;
        @endphp
        <div class="flex gap-2 flex-wrap">
            <div class="flex items-center gap-1.5 bg-sky-50 border border-sky-100 rounded-lg px-3 py-1.5 text-xs font-bold text-sky-700">
                <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                {{ $siswas->total() }} Siswa
            </div>
            <div class="flex items-center gap-1.5 bg-emerald-50 border border-emerald-100 rounded-lg px-3 py-1.5 text-xs font-bold text-emerald-700">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                {{ $totalBerkas }} Berkas
            </div>
            <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-100 rounded-lg px-3 py-1.5 text-xs font-bold text-amber-600">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                Rata-rata {{ $avgKelengkapan }}%
            </div>
        </div>
    </div>

    {{-- ── Filter + Search ──────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
        {{-- Status filter pills --}}
        <div class="flex gap-2">
            @foreach(['Aktif', 'Lulus', 'Semua'] as $st)
                <a href="{{ route('buku-induk.index', ['status' => $st, 'q' => $search]) }}"
                   class="px-4 py-1.5 rounded-full text-xs font-bold border-2 transition-all
                          {{ $statusFilter == $st
                             ? 'bg-sky-700 text-white border-sky-700 shadow-md shadow-sky-200'
                             : 'bg-white text-slate-500 border-slate-200 hover:border-sky-400 hover:text-sky-700 hover:bg-sky-50' }}">
                    {{ $st }}
                </a>
            @endforeach
        </div>

        {{-- Search form --}}
        <form method="GET" action="{{ route('buku-induk.index') }}" class="flex gap-2 flex-1 w-full sm:w-auto min-w-0">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <div class="relative flex-1 min-w-0">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" id="bi-live-search"
                       value="{{ $search }}"
                       placeholder="Cari nama atau NISN…"
                       class="w-full pl-9 pr-3 py-2 text-sm border-2 border-slate-200 rounded-xl
                              focus:border-sky-400 focus:outline-none focus:ring-4 focus:ring-sky-500/10
                              text-slate-700 font-medium bg-white transition-all">
            </div>
            <button type="submit"
                    class="px-4 py-2 text-white text-xs font-bold rounded-xl shadow-md transition-all
                           whitespace-nowrap cursor-pointer hover:-translate-y-0.5"
                    style="background:#0c4a6e; box-shadow:0 4px 12px rgba(12,74,110,.25);"
                    onmouseover="this.style.background='#08334d'"
                    onmouseout="this.style.background='#0c4a6e'">
                Cari
            </button>
        </form>
    </div>

    {{-- ── Flash Message ────────────────────────────────────────── --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <p class="text-xs font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    {{-- ── Data Table ──────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        @if($siswas->isEmpty())
        {{-- Empty state --}}
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
        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="bi-table">

                {{-- ── Head ── --}}
                <thead>
                    <tr style="background:linear-gradient(135deg, #0c4a6e 0%, #0369a1 100%);">
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100/80 w-10">#</th>

                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100
                                   cursor-pointer select-none transition-colors"
                            style="white-space:nowrap"
                            onclick="biSort(0, this)"
                            onmouseover="this.style.background='rgba(255,255,255,.08)'"
                            onmouseout="this.style.background=''">
                            <span class="flex items-center gap-1">
                                Nama Siswa
                                <svg class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 16V4m0 0L3 8m4-4l4 4m6 8V4m0 12l-4-4m4 4l4-4"/>
                                </svg>
                            </span>
                        </th>

                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100
                                   cursor-pointer select-none transition-colors"
                            style="white-space:nowrap"
                            onclick="biSort(1, this)"
                            onmouseover="this.style.background='rgba(255,255,255,.08)'"
                            onmouseout="this.style.background=''">
                            <span class="flex items-center gap-1">
                                NISN
                                <svg class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 16V4m0 0L3 8m4-4l4 4m6 8V4m0 12l-4-4m4 4l4-4"/>
                                </svg>
                            </span>
                        </th>

                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100
                                   cursor-pointer select-none transition-colors"
                            style="white-space:nowrap"
                            onclick="biSort(2, this)"
                            onmouseover="this.style.background='rgba(255,255,255,.08)'"
                            onmouseout="this.style.background=''">
                            <span class="flex items-center gap-1">
                                Kelas / Rombel Terakhir
                                <svg class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 16V4m0 0L3 8m4-4l4 4m6 8V4m0 12l-4-4m4 4l4-4"/>
                                </svg>
                            </span>
                        </th>

                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100"
                            style="white-space:nowrap">
                            Status
                        </th>

                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100
                                   cursor-pointer select-none transition-colors"
                            style="white-space:nowrap"
                            onclick="biSort(4, this)"
                            onmouseover="this.style.background='rgba(255,255,255,.08)'"
                            onmouseout="this.style.background=''">
                            <span class="flex items-center gap-1">
                                Kelengkapan Data
                                <svg class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 16V4m0 0L3 8m4-4l4 4m6 8V4m0 12l-4-4m4 4l4-4"/>
                                </svg>
                            </span>
                        </th>

                        <th class="py-3.5 px-4 text-center text-[0.62rem] font-bold uppercase tracking-widest text-sky-100"
                            style="white-space:nowrap">
                            Aksi
                        </th>
                    </tr>
                </thead>

                {{-- ── Body ── --}}
                <tbody id="bi-tbody">
                    @foreach($siswas as $index => $siswa)
                    @php
                        $bi          = $bukuIndukMap[$siswa->nisn] ?? null;
                        $kelengkapan = $bi ? $bi->kelengkapan : 0;

                        // Progress gradient
                        $progressBg = $kelengkapan >= 80
                            ? 'background:linear-gradient(90deg,#10b981,#34d399)'
                            : ($kelengkapan >= 40
                                ? 'background:linear-gradient(90deg,#f59e0b,#fbbf24)'
                                : 'background:linear-gradient(90deg,#f43f5e,#fb7185)');

                        // Percentage text color
                        $pctColor = $kelengkapan >= 80
                            ? 'color:#059669'
                            : ($kelengkapan >= 40 ? 'color:#d97706' : 'color:#e11d48');

                        // Status badge colors
                        $badgeStyle = match($siswa->status) {
                            'Aktif'         => 'background:#d1fae5;color:#065f46',
                            'Lulus'         => 'background:#e0f2fe;color:#0369a1',
                            'Keluar/Mutasi' => 'background:#ffe4e6;color:#9f1239',
                            default         => 'background:#f1f5f9;color:#475569',
                        };

                        // Avatar bg — sky blue tone matching sidebar
                        $avatarStyle = 'background:linear-gradient(135deg,#e0f2fe,#bae6fd);color:#0369a1';
                    @endphp

                    <tr class="border-b border-slate-100 transition-colors bi-row"
                        style="border-left:3px solid transparent;"
                        onmouseover="this.style.background='#f0f9ff';this.style.borderLeftColor='#0c4a6e'"
                        onmouseout="this.style.background='';this.style.borderLeftColor='transparent'">

                        {{-- No --}}
                        <td class="py-3 px-4 text-xs text-slate-400 font-bold">
                            {{ $siswas->firstItem() + $index }}
                        </td>

                        {{-- Nama + Avatar --}}
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-xs flex-shrink-0"
                                     style="{{ $avatarStyle }}">
                                    {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                                </div>
                                <span class="font-semibold text-slate-800 text-sm leading-tight">
                                    {{ $siswa->nama }}
                                </span>
                            </div>
                        </td>

                        {{-- NISN --}}
                        <td class="py-3 px-4">
                            <span class="font-mono text-xs text-slate-500 tracking-wide bg-slate-50 px-2 py-0.5 rounded-md border border-slate-100">
                                {{ $siswa->nisn ?? '—' }}
                            </span>
                        </td>

                        {{-- Kelas / Rombel Terakhir --}}
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-sky-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="text-sm text-slate-700 font-medium">
                                    {{ $siswa->rombel_saat_ini ?? 'Tidak diketahui' }}
                                </span>
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.65rem] font-bold"
                                  style="{{ $badgeStyle }}">
                                {{ $siswa->status ?? 'Aktif' }}
                            </span>
                        </td>

                        {{-- Kelengkapan --}}
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2" style="min-width:130px">
                                <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full" style="width:{{ $kelengkapan }}%; {{ $progressBg }}"></div>
                                </div>
                                <span class="text-[0.7rem] font-black w-8 text-right" style="{{ $pctColor }}">
                                    {{ $kelengkapan }}%
                                </span>
                            </div>
                        </td>

                        {{-- Aksi --}}
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

        {{-- ── Footer: Pagination + Row count ── --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2
                    px-5 py-3 bg-slate-50 border-t border-slate-100">
            <p class="text-xs text-slate-400">
                Menampilkan
                <strong class="text-slate-600">{{ $siswas->firstItem() }}</strong>–<strong class="text-slate-600">{{ $siswas->lastItem() }}</strong>
                dari <strong class="text-slate-600">{{ $siswas->total() }}</strong> siswa
            </p>
            @if($siswas->hasPages())
            <div>
                {{ $siswas->appends(['q' => $search, 'status' => $statusFilter])->links() }}
            </div>
            @endif
        </div>
        @endif

    </div>{{-- .table card --}}

</div>

{{-- ── Inline JS: Sorting + Live Search ── --}}
<script>
(function () {
    // Live client-side search filter
    const liveSearch = document.getElementById('bi-live-search');
    const tbody      = document.getElementById('bi-tbody');

    if (liveSearch && tbody) {
        liveSearch.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            Array.from(tbody.querySelectorAll('tr')).forEach(row => {
                row.style.display = (!q || row.innerText.toLowerCase().includes(q)) ? '' : 'none';
            });
        });
    }

    // Column sort
    let _sortCol = -1, _sortAsc = true;

    window.biSort = function (colIndex) {
        if (!tbody) return;
        _sortAsc  = (_sortCol === colIndex) ? !_sortAsc : true;
        _sortCol  = colIndex;

        const rows = Array.from(tbody.querySelectorAll('tr'));
        rows.sort((a, b) => {
            // +1 because first td is row-number column
            const tdA = a.querySelectorAll('td')[colIndex + 1]?.innerText.trim() ?? '';
            const tdB = b.querySelectorAll('td')[colIndex + 1]?.innerText.trim() ?? '';
            const numA = parseFloat(tdA), numB = parseFloat(tdB);

            if (!isNaN(numA) && !isNaN(numB)) {
                return _sortAsc ? numA - numB : numB - numA;
            }
            return _sortAsc
                ? tdA.localeCompare(tdB, 'id')
                : tdB.localeCompare(tdA, 'id');
        });

        rows.forEach(r => tbody.appendChild(r));
    };
})();
</script>
@endsection
