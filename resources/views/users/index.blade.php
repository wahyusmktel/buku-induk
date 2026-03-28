@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('header_title', 'Manajemen Pengguna')
@section('breadcrumb', 'Manajemen Pengguna')

@section('content')
<div class="mb-6 flex justify-between items-center px-2">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Daftar Pengguna</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Kelola akses, role, dan data pengguna sistem.</p>
    </div>
    <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm shadow-sky-600/20 transition-all hover:shadow-md hover:-translate-y-0.5">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Pengguna
    </a>
</div>

@if(session('success'))
<div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <p class="text-sm font-semibold">{{ session('success') }}</p>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center gap-3">
    <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p class="text-sm font-semibold">{{ session('error') }}</p>
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50/80 text-slate-500 uppercase text-xs font-extrabold tracking-wider border-b border-slate-100">
                <tr>
                    <th class="py-4 px-6">Informasi Pengguna</th>
                    <th class="py-4 px-6">Role Akses</th>
                    <th class="py-4 px-6">Tanggal Bergabung</th>
                    <th class="py-4 px-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-500 to-indigo-500 text-white flex items-center justify-center font-bold text-sm shadow-sm shrink-0">
                                {{ substr($user->name, 0, 2) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-base">{{ $user->name }}</p>
                                <p class="text-xs text-slate-400 font-medium">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($user->roles as $role)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-sky-50 text-sky-700 border border-sky-100 shadow-sm">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                            @if($user->roles->isEmpty())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                    Tidak ada
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="py-4 px-6 font-medium text-slate-500">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('users.edit', $user) }}" class="p-2 text-sky-600 hover:bg-sky-50 rounded-lg transition-colors" title="Edit Data">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Data">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-12 h-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <p class="font-semibold text-lg">Belum ada pengguna</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
