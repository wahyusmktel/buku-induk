@extends('layouts.app')

@section('title', 'Riwayat Aktivitas Sistem')

@section('content')
<div class="px-4 py-6 sm:px-0">
    {{-- Breadcrumb --}}
    <nav class="flex mb-6 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="ml-1 md:ml-2 text-slate-400">Riwayat Aktivitas</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Header Section --}}
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Aktivitas</h1>
            <p class="text-slate-500 text-base mt-2 max-w-2xl">Audit log komprehensif untuk memantau setiap perubahan data, alur ekspor, dan aktivitas keamanan sistem.</p>
        </div>
        
        <div class="flex items-center gap-3 self-end lg:self-auto">
            <a href="{{ route('activities.export', request()->all()) }}" class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-emerald-200/50 hover:-translate-y-0.5 active:translate-y-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export Excel
            </a>
        </div>
    </div>

    {{-- Filters Card --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 mb-6">
        <form action="{{ route('activities.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cari Aktivitas</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="pl-10 pr-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all w-full shadow-inner" 
                        placeholder="Nama user, deskripsi...">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Kategori</label>
                <select name="type" class="w-full pl-4 pr-10 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 transition-all shadow-inner">
                    <option value="">Semua Kategori</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $type)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal</label>
                <input type="date" name="date" value="{{ request('date') }}" 
                    class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 transition-all shadow-inner text-slate-600">
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-all shadow-md shadow-indigo-200 font-bold flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Terapkan Filter
                </button>

                @if(request()->anyFilled(['search', 'type', 'date']))
                    <a href="{{ route('activities.index') }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-all shadow-sm flex items-center justify-center" title="Reset Filter">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Activity Feed --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4 w-12 text-center">No</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Pengguna</th>
                        <th class="px-6 py-4">Aktivitas</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4 text-right">Detail Teknis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-center font-bold text-slate-400">
                            {{ $activities->firstItem() + $loop->index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-slate-800 font-medium">{{ $activity->created_at->translatedFormat('d M Y') }}</div>
                            <div class="text-slate-400 text-xs mt-0.5">{{ $activity->created_at->format('H:i:s') }} ({{ $activity->created_at->diffForHumans() }})</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    {{ strtoupper(substr($activity->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-800">{{ $activity->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $activity->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-slate-700 leading-relaxed max-w-md">
                                {{ $activity->description }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $badgeColors = [
                                    'dapodik_import' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'siswa_update' => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'siswa_delete' => 'bg-rose-50 text-rose-700 border-rose-100',
                                    'tahun_pelajaran_add' => 'bg-sky-50 text-sky-700 border-sky-100',
                                    'tahun_pelajaran_activate' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                    'tahun_pelajaran_copy' => 'bg-purple-50 text-purple-700 border-purple-100',
                                    'tahun_pelajaran_delete' => 'bg-rose-50 text-rose-700 border-rose-100',
                                    'mass_export_start' => 'bg-violet-50 text-violet-700 border-violet-100',
                                    'login' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'logout' => 'bg-slate-50 text-slate-700 border-slate-100',
                                ];
                                $color = $badgeColors[$activity->type] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                            @endphp
                            <span class="px-2.5 py-1 {{ $color }} rounded-full text-[0.65rem] font-bold uppercase tracking-wide border shadow-sm">
                                {{ str_replace('_', ' ', $activity->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="text-slate-500 text-xs font-mono">{{ $activity->ip_address }}</div>
                            <div class="text-[0.65rem] text-slate-400 mt-1 truncate max-w-[120px]" title="{{ $activity->user_agent }}">
                                {{ $activity->user_agent }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-12 h-12 mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <p class="text-sm font-medium">Belum ada riwayat aktivitas yang tercatat sesuai kriteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($activities->hasPages())
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            {{ $activities->links() }}
        </div>
        @endif
    </div>

    {{-- Stats Summary (Optional) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <div class="text-xs text-slate-500 font-medium uppercase tracking-wider">Total Aktivitas</div>
                <div class="text-xl font-bold text-slate-800">{{ number_format(\App\Models\Activity::count()) }}</div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="text-xs text-slate-500 font-medium uppercase tracking-wider">Aktivitas Hari Ini</div>
                <div class="text-xl font-bold text-slate-800">{{ number_format(\App\Models\Activity::whereDate('created_at', today())->count()) }}</div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <div class="text-xs text-slate-500 font-medium uppercase tracking-wider">Update Profil Siswa</div>
                <div class="text-xl font-bold text-slate-800">{{ number_format(\App\Models\Activity::where('type', 'siswa_update')->count()) }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
