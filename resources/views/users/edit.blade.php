@extends('layouts.app')

@section('title', 'Perbarui Pengguna Sistem')
@section('header_title', 'Perbarui Data Pengguna')
@section('breadcrumb')
    <a href="{{ route('users.index') }}" class="hover:text-sky-600 transition-colors">Manajemen Pengguna</a>
    <span class="text-slate-300 mx-1">/</span>
    <span class="text-slate-800 font-semibold">{{ $user->name }}</span>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Perbarui Pengguna</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Ubah nama, email, beserta roles dari pengguna.</p>
        </div>
        <div class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-xl text-xs font-bold border border-indigo-200 shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Sejak {{ $user->created_at->format('M Y') }}
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <form action="{{ route('users.update', $user) }}" method="POST" class="p-6 md:p-8">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring focus:ring-sky-500/20 transition-all font-medium text-slate-800 @error('name') border-rose-300 ring-rose-300/20 @enderror">
                    @error('name')
                        <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Alamat Email Akses</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring focus:ring-sky-500/20 transition-all font-medium text-slate-800 @error('email') border-rose-300 ring-rose-300/20 @enderror">
                    @error('email')
                        <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Info -->
                <div class="p-4 bg-amber-50 rounded-xl border border-amber-200 text-sm font-medium text-amber-800 flex gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Abaikan input sandi di bawah ini jika Anda tidak ingin merubah sandinya.
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-100">
                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi Baru (Opsional)</label>
                        <input type="password" name="password" id="password"
                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring focus:ring-sky-500/20 transition-all font-medium text-slate-800 @error('password') border-rose-300 ring-rose-300/20 @enderror"
                            placeholder="Abaikan bila tak diubah">
                        @error('password')
                            <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Ulangi Sandi Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring focus:ring-sky-500/20 transition-all font-medium text-slate-800"
                            placeholder="Ulangi hanya bila merubah">
                    </div>
                </div>

                <!-- Roles -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-4">Pilih Hak Akses (Role)</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($roles as $role)
                            <label class="relative flex items-center gap-3 p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-sky-50/50 hover:border-sky-200 transition-all group has-[:checked]:border-sky-500 has-[:checked]:bg-sky-50/50 has-[:checked]:shadow-sm">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                    class="w-4 h-4 text-sky-600 border-slate-300 rounded focus:ring-sky-500 focus:ring-2 transition-all cursor-pointer"
                                    @if(is_array(old('roles', $userRoles)) && in_array($role->name, old('roles', $userRoles))) checked @endif>
                                <span class="text-sm font-bold text-slate-700 group-hover:text-sky-800 transition-colors block">
                                    {{ $role->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('roles')
                        <p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all">
                    Kembali
                </a>
                <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold rounded-xl shadow-sm shadow-sky-600/20 transition-all hover:shadow-md cursor-pointer flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
