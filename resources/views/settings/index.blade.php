@extends('layouts.app')

@section('title', 'Konfigurasi Sistem')

@section('header_title', 'Konfigurasi Sistem')

@section('breadcrumb')
    <nav class="flex text-sm font-medium text-slate-500 mb-6" aria-label="Breadcrumb">
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
                    <span class="ml-1 md:ml-2 text-slate-400 font-semibold uppercase tracking-wider text-[0.7rem]">Konfigurasi Sistem</span>
                </div>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="max-w-5xl mx-auto">
    
    <div class="mb-8 flex flex-col gap-2">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Pengaturan Dokumen Buku Induk</h2>
        <p class="text-slate-500 text-sm">Sesuaikan informasi identitas sekolah, kepala sekolah, serta stempel yang akan digunakan pada fitur cetak Buku Induk dan dokumen lainnya.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <h3 class="text-sm font-bold text-emerald-800">Berhasil!</h3>
                <p class="text-sm text-emerald-600 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- 1. Kartu Informasi Kepala Sekolah --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <h3 class="font-bold text-slate-700">Informasi Kepala Sekolah</h3>
                </div>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="kepsek_nama" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Terang Kepala Sekolah</label>
                    <input type="text" id="kepsek_nama" name="kepsek_nama" 
                           value="{{ old('kepsek_nama', $settings['kepsek_nama'] ?? '') }}"
                           class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-inner"
                           placeholder="Contoh: Drs. H. Ahmad Dahlan, M.Pd.">
                    @error('kepsek_nama') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="kepsek_nip" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIP / NIK Kepala Sekolah</label>
                    <input type="text" id="kepsek_nip" name="kepsek_nip" 
                           value="{{ old('kepsek_nip', $settings['kepsek_nip'] ?? '') }}"
                           class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-inner"
                           placeholder="Contoh: 19700101 199512 1 001">
                    @error('kepsek_nip') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- 2. Detail Pengesahan Buku Induk --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <h3 class="font-bold text-slate-700">Detail Tempat & Tanggal Dokumen</h3>
                </div>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="buku_induk_kota" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kota / Tempat Penerbitan</label>
                    <input type="text" id="buku_induk_kota" name="buku_induk_kota" 
                           value="{{ old('buku_induk_kota', $settings['buku_induk_kota'] ?? '') }}"
                           class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-inner"
                           placeholder="Contoh: Gisting">
                    <p class="text-[0.65rem] text-slate-400 mt-1">Akan muncul sebelum tanggal di dokumen. (Contoh cetak: <strong>Gisting</strong>, 24 April 2024)</p>
                    @error('buku_induk_kota') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="buku_induk_tanggal" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Format / Tanggal Default (Opsional)</label>
                    <input type="text" id="buku_induk_tanggal" name="buku_induk_tanggal" 
                           value="{{ old('buku_induk_tanggal', $settings['buku_induk_tanggal'] ?? '') }}"
                           class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-inner"
                           placeholder="Contoh: 15 Juli 2024">
                    <p class="text-[0.65rem] text-slate-400 mt-1">Kosongkan jika Anda ingin aplikasi menggunakan tanggal saat dokumen diunduh/dicetak.</p>
                    @error('buku_induk_tanggal') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- 3. Pengaturan Ukuran & Margin Kertas --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ paperSize: '{{ old('paper_size', $settings['paper_size'] ?? 'a4') }}' }">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <h3 class="font-bold text-slate-700">Pengaturan Kertas & Margin PDF</h3>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Pilihan Kertas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="paper_size" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ukuran Kertas</label>
                        <select id="paper_size" name="paper_size" x-model="paperSize" class="w-full pl-4 pr-10 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 transition-all shadow-inner">
                            <option value="a4">A4 (210 x 297 mm)</option>
                            <option value="folio">F4 / Folio (215.9 x 330 mm)</option>
                            <option value="legal">Legal (215.9 x 355.6 mm)</option>
                            <option value="letter">Letter (215.9 x 279.4 mm)</option>
                            <option value="custom">Kustom (Atur Manual)</option>
                        </select>
                        <p class="text-[0.65rem] text-slate-400 mt-1">Standar yang sering digunakan di Indonesia adalah A4 / F4(Folio).</p>
                    </div>

                    <!-- Kustom dimensi kertas -->
                    <div x-show="paperSize === 'custom'" x-cloak class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lebar <span class="text-xs font-normal text-slate-400">(mm)</span></label>
                            <input type="number" step="0.1" name="paper_width" value="{{ old('paper_width', $settings['paper_width'] ?? '210') }}" :disabled="paperSize !== 'custom'" class="w-full rounded-lg border-slate-300 focus:border-sky-500 shadow-sm text-sm disabled:bg-slate-100">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tinggi <span class="text-xs font-normal text-slate-400">(mm)</span></label>
                            <input type="number" step="0.1" name="paper_height" value="{{ old('paper_height', $settings['paper_height'] ?? '297') }}" :disabled="paperSize !== 'custom'" class="w-full rounded-lg border-slate-300 focus:border-sky-500 shadow-sm text-sm disabled:bg-slate-100">
                        </div>
                    </div>
                </div>

                <!-- Pengaturan Margin -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Pengaturan Tepi (Margin) - dalam Centimeter (cm)</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-[0.65rem] font-bold text-slate-400 uppercase mb-1">Atas (Top)</label>
                            <input type="number" step="0.1" name="margin_top" value="{{ old('margin_top', $settings['margin_top'] ?? '2.5') }}" class="w-full px-3 py-2 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-700 text-center shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[0.65rem] font-bold text-slate-400 uppercase mb-1">Kanan (Right)</label>
                            <input type="number" step="0.1" name="margin_right" value="{{ old('margin_right', $settings['margin_right'] ?? '2.5') }}" class="w-full px-3 py-2 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-700 text-center shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[0.65rem] font-bold text-slate-400 uppercase mb-1">Bawah (Bottom)</label>
                            <input type="number" step="0.1" name="margin_bottom" value="{{ old('margin_bottom', $settings['margin_bottom'] ?? '2.5') }}" class="w-full px-3 py-2 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-700 text-center shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[0.65rem] font-bold text-slate-400 uppercase mb-1">Kiri (Left)</label>
                            <input type="number" step="0.1" name="margin_left" value="{{ old('margin_left', $settings['margin_left'] ?? '2.5') }}" class="w-full px-3 py-2 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-700 text-center shadow-inner">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Aset Gambar (Kop, Logo, TTD, Stempel) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <h3 class="font-bold text-slate-700">Aset Visual (Logo, Kop & Pengesahan)</h3>
                </div>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Kop Surat --}}
                <div class="bg-indigo-50/30 border border-indigo-100 rounded-2xl p-5 relative group">
                    <label class="block text-xs font-bold text-indigo-600 uppercase tracking-wider mb-4 border-b border-indigo-100 pb-2">Kop Surat Sekolah</label>
                    
                    @if(!empty($settings['sekolah_kop']))
                        <div class="mb-4 aspect-[4/1] bg-white border border-slate-200 rounded-lg overflow-hidden flex items-center justify-center p-2 relative">
                            <img src="{{ Storage::url($settings['sekolah_kop']) }}" alt="Kop Surat" class="max-h-full max-w-full object-contain">
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-900/60 to-transparent p-2">
                                <span class="text-[0.65rem] text-white font-semibold">Saat Ini: Gambar Tersimpan</span>
                            </div>
                        </div>
                    @else
                        <div class="mb-4 aspect-[4/1] bg-white border border-slate-200 border-dashed rounded-lg flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-xs font-medium">Belum ada Kop Surat</span>
                        </div>
                    @endif

                    <input type="file" name="sekolah_kop" accept="image/*" class="block w-full text-xs text-slate-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-xl file:border-0
                        file:text-xs file:font-bold
                        file:bg-indigo-600 file:text-white
                        hover:file:bg-indigo-700 cursor-pointer transition-all
                    "/>
                    <p class="text-[0.65rem] text-slate-400 mt-2">Saran ukuran: Rasio melebar (contoh: 800x200px).</p>
                    @error('sekolah_kop') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Logo Sekolah --}}
                <div class="bg-amber-50/30 border border-amber-100 rounded-2xl p-5 relative group">
                    <label class="block text-xs font-bold text-amber-600 uppercase tracking-wider mb-4 border-b border-amber-100 pb-2">Logo Sekolah</label>
                    
                    @if(!empty($settings['sekolah_logo']))
                        <div class="mb-4 w-24 h-24 mx-auto bg-white border border-slate-200 rounded-lg overflow-hidden flex items-center justify-center p-2 shadow-sm">
                            <img src="{{ Storage::url($settings['sekolah_logo']) }}" alt="Logo Sekolah" class="max-h-full max-w-full object-contain">
                        </div>
                    @else
                        <div class="mb-4 w-24 h-24 mx-auto bg-white border border-slate-200 border-dashed rounded-lg flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                    @endif

                    <input type="file" name="sekolah_logo" accept="image/*" class="block w-full text-xs text-slate-500
                        file:mr-3 file:py-1.5 file:px-3
                        file:rounded-full file:border-0
                        file:text-xs file:font-semibold
                        file:bg-sky-50 file:text-sky-700
                        hover:file:bg-sky-100 cursor-pointer
                    "/>
                    <p class="text-[0.65rem] text-slate-400 mt-2">Dianjurkan berformat PNG/Transparan dengan bentuk persegi.</p>
                    @error('sekolah_logo') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Tanda Tangan Kepsek --}}
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 relative group">
                    <label class="block text-sm font-bold text-slate-700 mb-3 border-b border-slate-200 pb-2">Tanda Tangan Kepala Sekolah (TTD)</label>
                    
                    @if(!empty($settings['kepsek_ttd']))
                        <div class="mb-4 aspect-video sm:w-48 mx-auto bg-white border border-slate-200 rounded-lg overflow-hidden flex items-center justify-center p-2 shadow-sm">
                            <img src="{{ Storage::url($settings['kepsek_ttd']) }}" alt="TTD Kepsek" class="max-h-full max-w-full object-contain">
                        </div>
                    @else
                        <div class="mb-4 aspect-video sm:w-48 mx-auto bg-white border border-slate-200 border-dashed rounded-lg flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </div>
                    @endif

                    <input type="file" name="kepsek_ttd" accept="image/png,image/jpeg" class="block w-full text-xs text-slate-500
                        file:mr-3 file:py-1.5 file:px-3
                        file:rounded-full file:border-0
                        file:text-xs file:font-semibold
                        file:bg-sky-50 file:text-sky-700
                        hover:file:bg-sky-100 cursor-pointer
                    "/>
                    <p class="text-[0.65rem] text-slate-400 mt-2">File TTD pastikan dengan background transparan (PNG).</p>
                    @error('kepsek_ttd') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Stempel Sekolah --}}
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 relative group">
                    <label class="block text-sm font-bold text-slate-700 mb-3 border-b border-slate-200 pb-2">Stempel Institusi / Sekolah</label>
                    
                    @if(!empty($settings['sekolah_stempel']))
                        <div class="mb-4 w-32 h-32 mx-auto bg-white border border-slate-200 rounded-lg overflow-hidden flex items-center justify-center p-2 shadow-sm">
                            <img src="{{ Storage::url($settings['sekolah_stempel']) }}" alt="Stempel Sekolah" class="max-h-full max-w-full object-contain mix-blend-multiply">
                        </div>
                    @else
                        <div class="mb-4 w-32 h-32 mx-auto bg-white border border-slate-200 border-dashed rounded-lg flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                    @endif

                    <input type="file" name="sekolah_stempel" accept="image/png,image/jpeg" class="block w-full text-xs text-slate-500
                        file:mr-3 file:py-1.5 file:px-3
                        file:rounded-full file:border-0
                        file:text-xs file:font-semibold
                        file:bg-sky-50 file:text-sky-700
                        hover:file:bg-sky-100 cursor-pointer
                    "/>
                    <p class="text-[0.65rem] text-slate-400 mt-2">File Stempel (PNG transparan sangat disarankan).</p>
                    @error('sekolah_stempel') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200 mt-10">
            <button type="reset" onclick="window.location.reload()" class="px-6 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 focus:ring-4 focus:ring-slate-100 transition-all">
                Batal
            </button>
            <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white bg-indigo-600 border border-transparent rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500/20 shadow-lg shadow-indigo-200 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                Simpan & Perbarui
            </button>
        </div>

    </form>
</div>
@endsection
