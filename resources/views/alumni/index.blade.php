@extends('layouts.app')

@section('title', 'Data Alumni')
@section('header_title', 'Data Alumni')
@section('breadcrumb')
    <span class="text-slate-500">Arsip Siswa</span>
    <span class="text-slate-300 mx-1">/</span>
    <span class="text-slate-800">Alumni</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- ── Page Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Data Alumni</h2>
            <p class="text-xs text-slate-400 mt-0.5">
                Siswa yang telah lulus dan tercatat sebagai alumni.
            </p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <div class="flex items-center gap-1.5 bg-violet-50 border border-violet-100 rounded-lg px-3 py-1.5 text-xs font-bold text-violet-700">
                <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                {{ $alumni->total() }} Alumni
            </div>
        </div>
    </div>

    {{-- ══ FILTER AREA ══ --}}
    <form method="GET" action="{{ route('alumni.index') }}" id="filter-form"
          class="flex flex-wrap gap-2 items-center">

        {{-- ① Filter Tahun Pelajaran --}}
        <div class="relative shrink-0">
            <select name="tahun_id"
                    onchange="this.form.submit()"
                    class="appearance-none pl-3 pr-8 py-2 text-xs font-bold border-2 rounded-xl cursor-pointer transition-all
                           bg-white text-slate-600 border-slate-200
                           hover:border-violet-400 focus:outline-none focus:border-violet-500 focus:ring-2 focus:ring-violet-500/10"
                    style="{{ $tahunId ? 'border-color:#7c3aed;color:#7c3aed;background:#f5f3ff;' : '' }}">
                <option value="">Semua Tahun Pelajaran</option>
                @foreach($tahunList as $tp)
                    <option value="{{ $tp->id }}" {{ $tahunId == $tp->id ? 'selected' : '' }}>
                        {{ $tp->tahun }} - {{ $tp->semester }}
                    </option>
                @endforeach
            </select>
            <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 w-3 h-3 text-slate-400 pointer-events-none"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        <div class="w-px h-7 bg-slate-200 shrink-0 hidden sm:block"></div>

        {{-- ② Search --}}
        <div class="relative flex-1" style="min-width:180px;">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="q" id="alumni-search"
                   value="{{ $search }}"
                   placeholder="Cari nama siswa, NISN, atau NIS…"
                   class="w-full pl-9 pr-3 py-2 text-sm border-2 border-slate-200 rounded-xl
                          focus:border-violet-400 focus:outline-none focus:ring-4 focus:ring-violet-500/10
                          text-slate-700 font-medium bg-white transition-all">
        </div>

        {{-- ③ Cari --}}
        <button type="submit"
                class="px-4 py-2 text-white text-xs font-bold rounded-xl transition-all
                       whitespace-nowrap cursor-pointer hover:-translate-y-0.5 shrink-0"
                style="background:#5b21b6;box-shadow:0 4px 10px rgba(91,33,182,.2);"
                onmouseover="this.style.background='#4c1d95'"
                onmouseout="this.style.background='#5b21b6'">
            Cari
        </button>

        <div class="w-px h-7 bg-slate-200 shrink-0 hidden sm:block"></div>

        {{-- ④ Per-page --}}
        <div class="flex items-center gap-1.5 shrink-0">
            <span class="text-xs text-slate-500 font-semibold whitespace-nowrap hidden sm:inline">Tampilkan</span>
            <div class="relative">
                <select name="per_page" onchange="this.form.submit()"
                        class="appearance-none pl-3 pr-7 py-2 text-xs font-bold border-2 border-slate-200 rounded-xl
                               bg-white text-slate-600 cursor-pointer transition-all
                               hover:border-violet-400 focus:outline-none focus:border-violet-500 focus:ring-2 focus:ring-violet-500/10">
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
        @if($search || $tahunId || $perPage != 20)
        <a href="{{ route('alumni.index') }}"
           class="px-3 py-2 text-xs font-bold text-slate-400 border-2 border-slate-200 rounded-xl
                  hover:border-rose-300 hover:text-rose-500 hover:bg-rose-50 transition-all whitespace-nowrap shrink-0">
            ✕ Reset
        </a>
        @endif
    </form>

    {{-- Flash --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-5"
         class="fixed z-50 bottom-8 right-8 bg-slate-800 text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3 font-semibold text-sm">
        <div class="flex-shrink-0 bg-emerald-500 rounded-full p-1.5"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    {{-- ── Data Table ── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        @if($alumni->isEmpty())
        <div class="py-20 text-center px-6">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4"
                 style="background:rgba(91,33,182,.08)">
                <svg class="w-9 h-9 text-violet-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 14l9-5-9-5-9 5 9 5zm0 7l-9-5 9-5 9 5-9 5zm0-14l9 5-9 5-9-5 9-5z"/>
                </svg>
            </div>
            <h3 class="text-sm font-bold text-slate-700">Belum Ada Data Alumni</h3>
            <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Siswa yang berstatus "Lulus" akan otomatis muncul di halaman ini.</p>
        </div>

        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="alumni-table">
                <thead>
                    <tr style="background:linear-gradient(135deg, #4c1d95 0%, #7c3aed 100%);">
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest"
                            style="color:rgba(221,214,254,.7); width:42px;">#</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-violet-100"
                            style="white-space:nowrap">Nama Siswa</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-violet-100"
                            style="white-space:nowrap">NISN</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-violet-100"
                            style="white-space:nowrap">NIS/NIPD</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-violet-100"
                            style="white-space:nowrap">Jenis Kelamin</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-violet-100"
                            style="white-space:nowrap">Tahun Pelajaran</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-violet-100"
                            style="white-space:nowrap">Kelas Terakhir</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-violet-100"
                            style="white-space:nowrap">No. Ijazah</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-violet-100"
                            style="white-space:nowrap">Tgl. Lulus</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-violet-100"
                            style="white-space:nowrap">Lanjut Ke</th>
                        <th class="py-3.5 px-4 text-center text-[0.62rem] font-bold uppercase tracking-widest text-violet-100"
                            style="white-space:nowrap">Buku Induk</th>
                    </tr>
                </thead>
                <tbody id="alumni-tbody">
                    @foreach($alumni as $index => $siswa)
                    @php
                        $bi = $bukuIndukMap[$siswa->nisn] ?? null;
                    @endphp
                    <tr class="border-b border-slate-100 transition-colors"
                        style="border-left:3px solid transparent;"
                        onmouseover="this.style.background='#faf5ff';this.style.borderLeftColor='#7c3aed'"
                        onmouseout="this.style.background='';this.style.borderLeftColor='transparent'">

                        <td class="py-3 px-4 text-xs text-slate-400 font-bold">{{ $alumni->firstItem() + $index }}</td>

                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-xs flex-shrink-0"
                                     style="background:linear-gradient(135deg,#ede9fe,#ddd6fe);color:#7c3aed">
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
                            <span class="font-mono text-xs text-slate-500 bg-slate-50 px-2 py-0.5 rounded-md border border-slate-100">
                                {{ $siswa->nipd ?? '—' }}
                            </span>
                        </td>

                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[0.65rem] font-bold uppercase tracking-wider
                                {{ $siswa->jk == 'L' ? 'bg-sky-100 text-sky-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </td>

                        <td class="py-3 px-4">
                            @if($siswa->tahunPelajaran)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-violet-700 bg-violet-50 border border-violet-100 px-2.5 py-1 rounded-lg">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $siswa->tahunPelajaran->tahun }} / {{ $siswa->tahunPelajaran->semester }}
                            </span>
                            @else
                            <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>

                        <td class="py-3 px-4">
                            <span class="text-sm font-medium text-slate-600">
                                {{ $siswa->rombel_saat_ini ?? '—' }}
                            </span>
                        </td>

                        <td class="py-3 px-4">
                            <span class="font-mono text-xs text-slate-600 bg-slate-50 px-2 py-0.5 rounded-md border border-slate-100">
                                {{ $bi->no_ijazah ?? '—' }}
                            </span>
                        </td>

                        <td class="py-3 px-4">
                            <span class="text-xs text-slate-600 font-medium">
                                {{ $bi && $bi->tgl_lulus ? $bi->tgl_lulus->format('d/m/Y') : '—' }}
                            </span>
                        </td>

                        <td class="py-3 px-4">
                            <span class="text-xs text-slate-600 font-medium">
                                {{ $bi->lanjut_ke ?? '—' }}
                            </span>
                        </td>

                        <td class="py-3 px-4 text-center">
                            @if($bi && $siswa->nisn)
                                <a href="{{ route('buku-induk.show', $siswa->nisn) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[0.7rem] font-bold rounded-lg
                                          border transition-all hover:-translate-y-0.5"
                                   style="background:#ede9fe;color:#7c3aed;border-color:#ddd6fe;"
                                   onmouseover="this.style.background='#5b21b6';this.style.color='#fff';this.style.borderColor='#5b21b6';this.style.boxShadow='0 4px 12px rgba(91,33,182,.3)'"
                                   onmouseout="this.style.background='#ede9fe';this.style.color='#7c3aed';this.style.borderColor='#ddd6fe';this.style.boxShadow=''">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
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
                Menampilkan <strong class="text-slate-600">{{ $alumni->firstItem() }}</strong>–<strong class="text-slate-600">{{ $alumni->lastItem() }}</strong>
                dari <strong class="text-slate-600">{{ $alumni->total() }}</strong> alumni
            </p>
            @if($alumni->hasPages())
            <div>
                {{ $alumni->links() }}
            </div>
            @endif
        </div>
        @endif

    </div>{{-- .table card --}}

</div>
@endsection
