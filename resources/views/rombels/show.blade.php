@extends('layouts.app')

@section('title', 'Anggota Rombel ' . $rombel->nama)
@section('header_title')
    {{ $rombel->nama }}
    @if($rombel->tingkat)
        <span class="ml-2 px-3 py-1 bg-amber-500/20 text-amber-200 text-xs font-black rounded-lg uppercase tracking-wider border border-amber-500/30 shadow-inner">Tingkat {{ $rombel->tingkat }}</span>
    @endif
@endsection
@section('breadcrumb')
    <a href="{{ route('rombels.index') }}" class="hover:text-sky-600">Daftar Rombel</a>
    <span class="text-slate-300 mx-1">/</span>
    <span class="text-slate-800">{{ $rombel->nama }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="bg-slate-50/50 px-8 py-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-black text-slate-800 tracking-tight">Anggota Rombongan Belajar</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Total anggota terdaftar: <span class="font-bold text-sky-600">{{ $rombel->siswas->count() }} Siswa</span></p>
            </div>
            <a href="{{ route('rombels.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-sky-600 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30 text-slate-400 uppercase text-[0.7rem] font-black tracking-widest border-b border-slate-100">
                        <th class="px-8 py-4 w-16 text-center">No</th>
                        <th class="px-4 py-4 min-w-[200px]">Nama Lengkap</th>
                        <th class="px-4 py-4 min-w-[150px]">NISN</th>
                        <th class="px-4 py-4 min-w-[150px]">NIK</th>
                        <th class="px-4 py-4 min-w-[100px]">JK</th>
                        <th class="px-8 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($rombel->siswas as $index => $siswa)
                    <tr class="hover:bg-sky-50/30 transition-colors group">
                        <td class="px-8 py-4 text-center text-sm font-bold text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center font-bold text-sm shadow-sm">
                                    {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700 group-hover:text-sky-700 transition-colors">{{ $siswa->nama }}</span>
                                    <span class="text-[0.7rem] text-slate-400 font-medium tracking-tight uppercase">Siswa Aktif</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm font-mono text-slate-600">{{ $siswa->nisn ?: '-' }}</td>
                        <td class="px-4 py-4 text-sm font-mono text-slate-600">{{ $siswa->nik ?: '-' }}</td>
                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-[0.65rem] font-bold uppercase tracking-wider {{ $siswa->jk == 'L' ? 'bg-sky-100 text-sky-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <a href="{{ route('siswas.show', $siswa->id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-sky-600 hover:text-sky-700 bg-sky-50 hover:bg-sky-100 px-3 py-1.5 rounded-lg transition-all border border-sky-100">
                                Profil
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center text-slate-400 font-medium">
                            Tidak ada anggota terdaftar di rombel ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
