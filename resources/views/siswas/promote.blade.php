@extends('layouts.app')

@section('title', 'Naik Kelas & Oplos Siswa')
@section('header_title', 'Promosi & Pengacakan Siswa')
@section('breadcrumb', 'Siswa / Naik Kelas')

@section('content')
<div x-data="{ 
    selectedStudents: [],
    allSelected: false,
    loading: false,
    toggleAll() {
        if (this.allSelected) {
            this.selectedStudents = [];
            this.allSelected = false;
        } else {
            this.selectedStudents = Array.from(document.querySelectorAll('input[name=\'siswa_ids[]\']')).map(el => el.value);
            this.allSelected = true;
        }
    }
}">
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Kenaikan Kelas <span class="text-indigo-600">&</span> Shuffling</h2>
        <p class="text-slate-500 font-medium mt-1 italic">Pindahkan siswa ke tahun ajaran baru dan atur rombongan belajar secara fleksibel.</p>
    </div>

    <!-- Filter & Selection Source -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                    Sumber Data (Asal)
                </h3>
                
                <form action="{{ route('siswas.promote.index') }}" method="GET" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 ml-1 uppercase">Sesi Akademik</label>
                        <select name="source_tahun_id" onchange="this.form.submit()" class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-indigo-100 transition-all">
                            @foreach($tahunPelajarans as $tp)
                                <option value="{{ $tp->id }}" {{ $sourceTahunId == $tp->id ? 'selected' : '' }}>
                                    {{ $tp->tahun }} - {{ $tp->semester }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 ml-1 uppercase">Kelas / Rombel</label>
                        <select name="source_rombel_id" onchange="this.form.submit()" class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-indigo-100 transition-all">
                            <option value="">-- Pilih Rombel --</option>
                            @foreach($rombels as $r)
                                <option value="{{ $r->id }}" {{ $sourceRombelId == $r->id ? 'selected' : '' }}>
                                    {{ $r->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                @if($sourceRombelId)
                <div class="mt-8 pt-8 border-t border-slate-50">
                    <div class="bg-indigo-50 rounded-2xl p-4 text-center">
                        <p class="text-xs font-bold text-indigo-700 uppercase tracking-tighter">Total Siswa Aktif</p>
                        <p class="text-3xl font-black text-indigo-800">{{ count($siswas) }}</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="bg-slate-900 rounded-3xl p-6 text-white shadow-xl shadow-slate-200">
                <h4 class="font-bold text-base mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Tips Oplos
                </h4>
                <p class="text-slate-400 text-xs leading-relaxed italic opacity-80">
                    Sistem akan menduplikat data siswa ke tahun ajaran tujuan. Buku Induk tetap tersambung melalui NISN. Gunakan fitur ini untuk mengatur kelas baru di setiap kenaikan tingkat.
                </p>
            </div>
        </div>

        <!-- Student List & Target -->
        <div class="lg:col-span-3">
            @if(!$sourceRombelId)
            <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-16 text-center">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h4 class="text-xl font-bold text-slate-700 tracking-tight">Belum Ada Data Terpilih</h4>
                <p class="text-slate-500 max-w-sm mx-auto mt-2 text-sm leading-relaxed">Silakan pilih <span class="font-bold text-slate-800">Sesi Akademik</span> dan <span class="font-bold text-slate-800">Rombel</span> asal pada panel di samping untuk memuat daftar siswa.</p>
            </div>
            @else
            <form action="{{ route('siswas.promote.store') }}" method="POST" @submit="loading = true">
                @csrf
                <input type="hidden" name="source_tahun_id" value="{{ $sourceTahunId }}">
                <input type="hidden" name="source_rombel_id" value="{{ $sourceRombelId }}">

                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-6 transition-all" :class="{ 'opacity-50 pointer-events-none': loading }">
                    <div class="bg-slate-50/50 p-4 border-b border-slate-100 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" @click="toggleAll()" x-model="allSelected" class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 transition-all cursor-pointer">
                            <span class="text-xs font-black text-slate-500 uppercase tracking-widest">Pilih Semua</span>
                        </div>
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full" x-text="selectedStudents.length + ' Siswa terpilih'"></span>
                    </div>
                    <div class="max-h-[500px] overflow-y-auto">
                        <table class="w-full text-left text-sm">
                            <tbody class="divide-y divide-slate-100">
                                @forelse($siswas as $siswa)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="py-3 px-6 w-12 text-center">
                                        <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}" x-model="selectedStudents" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                    </td>
                                    <td class="py-3 px-2">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-[10px] uppercase group-hover:bg-indigo-100 group-hover:text-indigo-700 transition-all shadow-sm">
                                                {{ substr($siswa->nama, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 leading-none mb-1">{{ $siswa->nama }}</p>
                                                <p class="text-[10px] text-slate-400 font-mono italic">{{ $siswa->nisn ?: 'NISN Tidak Ada' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-right font-medium text-slate-500 text-xs">
                                        {{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="p-8 text-center text-slate-400 italic">Tidak ada siswa aktif di rombel ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Action Panel (Target) -->
                <div class="bg-gradient-to-br from-indigo-900 to-indigo-800 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden" x-show="selectedStudents.length > 0" x-transition>
                    <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="absolute -left-12 -bottom-12 w-32 h-32 bg-indigo-400/10 rounded-full blur-2xl"></div>
                    
                    <div class="flex flex-col lg:flex-row gap-8 items-center relative z-10">
                        <div class="flex-1 space-y-6">
                            <h4 class="text-xl font-black italic border-b border-indigo-700/50 pb-3 tracking-tight">Konfigurasi Tujuan Promosi</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-indigo-300 mb-2 tracking-widest">Sesi Akademik Tujuan</label>
                                    <select name="target_tahun_id" required class="w-full bg-white/10 border-indigo-400/20 text-white rounded-2xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-indigo-400/30 transition-all outline-none appearance-none cursor-pointer">
                                        @foreach($targetSessions as $ts)
                                            <option value="{{ $ts->id }}" class="text-slate-900">{{ $ts->tahun }} - {{ $ts->semester }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-indigo-300 mb-2 tracking-widest">Rombel Tujuan (Oplos Ke)</label>
                                    <input type="text" name="target_rombel_nama" placeholder="Isi nama rombel baru..." required class="w-full bg-white/10 border border-indigo-400/20 text-white placeholder-indigo-300/50 rounded-2xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-indigo-400/30 transition-all outline-none">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-indigo-300 mb-2 tracking-widest">Status di Sesi Baru</label>
                                    <select name="promote_status" class="w-full bg-white/10 border-indigo-400/20 text-white rounded-2xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-indigo-400/30 transition-all outline-none appearance-none cursor-pointer">
                                        <option value="Aktif" class="text-slate-900">Aktif</option>
                                        <option value="Lulus" class="text-slate-900">Lulus</option>
                                    </select>
                                </div>
                                <div class="flex items-center pt-6">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" name="update_batch" value="1" class="w-5 h-5 rounded bg-white/10 border-indigo-400/20 text-indigo-500 focus:ring-0 transition-all group-hover:bg-white/20">
                                        <span class="text-xs font-bold text-indigo-100 select-none">Update Tahun Masuk (Angkatan)</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 min-w-[200px] w-full lg:w-auto">
                            <button type="submit" :disabled="loading" class="w-full py-5 bg-white text-indigo-800 hover:bg-indigo-50 rounded-3xl text-sm font-black uppercase tracking-widest shadow-xl shadow-indigo-900/50 transition-all active:scale-95 flex items-center justify-center gap-3 cursor-pointer">
                                <template x-if="!loading">
                                    <div class="flex items-center gap-3">
                                        <span>Proses Promosi</span>
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                                    </div>
                                </template>
                                <template x-if="loading">
                                    <svg class="animate-spin h-5 w-5 text-indigo-800" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </template>
                            </button>
                            <p class="text-[10px] text-center font-bold text-indigo-300 italic">Aksi ini tidak dapat dibatalkan.</p>
                        </div>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
