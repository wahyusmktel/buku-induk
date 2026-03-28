@extends('layouts.app')

@section('title', 'Manajemen Role')
@section('header_title', 'Manajemen Role')
@section('breadcrumb', 'Manajemen Role')

@section('content')
<div class="mb-6 flex justify-between items-center px-2">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Daftar Role</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Kelola jenis-jenis role yang tersedia untuk pengguna sistem.</p>
    </div>
    <a href="{{ route('roles.create') }}" class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm shadow-sky-600/20 transition-all hover:shadow-md hover:-translate-y-0.5">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Role
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
                    <th class="py-4 px-6">Nama Role</th>
                    <th class="py-4 px-6">Total Pengguna</th>
                    <th class="py-4 px-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($roles as $role)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="py-4 px-6">
                        <p class="font-bold text-slate-800 text-base flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full {{ $role->name == 'Super Admin' ? 'bg-amber-500' : 'bg-sky-500' }}"></span>
                            {{ $role->name }}
                        </p>
                    </td>
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $role->users_count }}
                        </span>
                        Pengguna
                    </td>
                    <td class="py-4 px-6 text-right">
                        @if($role->name !== 'Super Admin')
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('roles.edit', $role) }}" class="p-2 text-sky-600 hover:bg-sky-50 rounded-lg transition-colors" title="Edit Role">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus role ini? Ini juga akan menghapus kepemilikan user pada role ini.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Role">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                        @else
                            <span class="text-xs font-semibold text-amber-600 italic px-2">Protected</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-12 text-center text-slate-400">
                        <p class="font-semibold text-lg">Belum ada role tersedia.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($roles->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
        {{ $roles->links() }}
    </div>
    @endif
</div>
@endsection
