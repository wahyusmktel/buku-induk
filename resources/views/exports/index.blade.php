@extends('layouts.app')

@section('title', 'Export Massal Buku Induk')

@section('header_title', 'Eksport & Laporan')

@section('breadcrumb')
    <span class="text-slate-500">Laporan</span>
    <span class="text-slate-300 mx-1">/</span>
    <span class="text-slate-800 font-semibold">Cetak PDF Massal</span>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <h3 class="text-sm font-bold text-emerald-800">Berhasil!</h3>
                <p class="text-sm text-emerald-600 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Trigger Export --}}
        <div class="lg:col-span-1" x-data="exportManager()">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <h3 class="font-bold text-slate-700">Filter Export & Zip</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama File Export (Saran)</label>
                        <input type="text" x-model="form.name" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500/20 text-sm shadow-sm" placeholder="Contoh: Export Kelas VI Lulusan 2024">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih Angkatan (Tahun Pelajaran)</label>
                        <select x-model="form.tahun_id" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500/20 text-sm shadow-sm bg-white">
                            <option value="">Semua Tahun (Bahaya / Berat)</option>
                            @foreach($tahunPelajarans as $tp)
                                <option value="{{ $tp->id }}">{{ $tp->tahun }} - Semester {{ $tp->semester }} {{ $tp->is_aktif ? '(Sesi Aktif)' : '' }}</option>
                            @endforeach
                        </select>
                        <p class="text-[0.65rem] text-slate-400 mt-1">Export seluruh siswa pada tahun ajaran ini ke PDF.</p>
                    </div>

                    <button @click="startExport" :disabled="isProcessing" 
                            class="w-full mt-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm shadow-sm transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                        <template x-if="!isProcessing">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </template>
                        <template x-if="isProcessing">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </template>
                        <span x-text="isProcessing ? 'Proses Dimulai...' : 'Generate Format ZIP'"></span>
                    </button>
                </div>

                {{-- Progress Bar Interaktif --}}
                <div x-show="isProcessing || activeJobId" x-transition class="p-5 border-t border-slate-100 bg-indigo-50/50" x-cloak>
                    <div class="flex justify-between items-end mb-1.5">
                        <span class="text-xs font-bold text-indigo-900">Progres Pembuatan PDF</span>
                        <span class="text-xs font-bold text-indigo-700" x-text="progress + '%'"></span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden shadow-inner">
                        <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300 ease-out" :style="'width: ' + progress + '%'"></div>
                    </div>
                    <div class="flex justify-between mt-2">
                        <span class="text-[0.65rem] font-medium text-slate-500" x-text="statusText"></span>
                        <span class="text-[0.65rem] font-medium text-slate-500" x-text="processed + ' / ' + total + ' File'"></span>
                    </div>

                    <div x-show="downloadUrl" class="mt-4" x-cloak>
                        <a :href="downloadUrl" class="block w-full text-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg shadow-sm transition-colors cursor-pointer">
                            ⬇️ Unduh File ZIP Sekarang
                        </a>
                    </div>
                </div>
            </div>
            
            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('exportManager', () => ({
                        form: {
                            name: '',
                            tahun_id: ''
                        },
                        isProcessing: false,
                        activeJobId: null,
                        progress: 0,
                        processed: 0,
                        total: 0,
                        status: '',
                        statusText: 'Menunggu...',
                        downloadUrl: null,
                        pollInterval: null,

                        startExport() {
                            if(!this.form.name) {
                                alert('Harap isi Nama File Export terlebih dahulu.');
                                return;
                            }

                            this.isProcessing = true;
                            this.progress = 0;
                            this.downloadUrl = null;
                            this.statusText = 'Memasukkan ke dalam antrian Redis...';

                            fetch('{{ route('settings.update') }}'.replace('settings', 'exports'), { // Trik mengambil prefix root sementara
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(this.form)
                            }).then(res => res.json())
                              .then(data => {
                                  if(data.success) {
                                      this.activeJobId = data.job_id;
                                      this.startPolling();
                                  } else {
                                      alert('Terjadi kesalahan pada server.');
                                      this.isProcessing = false;
                                  }
                              });
                        },

                        startPolling() {
                            this.pollInterval = setInterval(() => {
                                fetch(`/exports/progress/${this.activeJobId}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        this.progress = data.percentage;
                                        this.processed = data.processed;
                                        this.total = data.total;
                                        this.status = data.status;

                                        if (this.status === 'processing') {
                                            this.statusText = 'Merender PDF menggunakan DOMPDF...';
                                        } else if (this.status === 'completed') {
                                            clearInterval(this.pollInterval);
                                            this.isProcessing = false;
                                            this.statusText = 'Sukses dipacking ke ZIP!';
                                            this.progress = 100;
                                            this.downloadUrl = `/exports/${this.activeJobId}/download`;
                                        } else if (this.status === 'failed') {
                                            clearInterval(this.pollInterval);
                                            this.isProcessing = false;
                                            this.statusText = 'Gagal: ' + data.error_message;
                                        }
                                    });
                            }, 1500); // Polling per 1.5 detik
                        }
                    }));
                });
            </script>
        </div>

        {{-- History Table --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden h-full">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
                    <svg class="w-5 h-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="font-bold text-slate-700">Riwayat Proses Sistem (10 Terakhir)</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Nama Export</th>
                                <th class="px-5 py-3 text-center">Status</th>
                                <th class="px-5 py-3 text-center">Data</th>
                                <th class="px-5 py-3 text-center">Tgl Proses</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($exportJobs as $job)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-3 font-medium text-slate-800">{{ $job->name }}</td>
                                <td class="px-5 py-3 text-center">
                                    @if($job->status === 'completed')
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-md text-[0.65rem] font-bold uppercase tracking-wide">Selesai</span>
                                    @elseif($job->status === 'processing')
                                        <span class="px-2 py-1 bg-sky-100 text-sky-700 rounded-md text-[0.65rem] font-bold uppercase tracking-wide">Diproses</span>
                                    @elseif($job->status === 'failed')
                                        <span class="px-2 py-1 bg-rose-100 text-rose-700 rounded-md text-[0.65rem] font-bold uppercase tracking-wide">Gagal</span>
                                    @else
                                        <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded-md text-[0.65rem] font-bold uppercase tracking-wide">Menunggu</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center text-xs font-semibold">
                                    {{ $job->processed_records }} / {{ $job->total_records }}
                                </td>
                                <td class="px-5 py-3 text-center text-xs">
                                    {{ $job->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-5 py-3 text-right flex items-center justify-end gap-2">
                                    @if($job->status === 'completed' && $job->file_path)
                                        <a href="{{ route('exports.download', $job->id) }}" class="p-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white rounded-lg transition-colors cursor-pointer" title="Unduh ZIP File">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        </a>
                                    @endif
                                    <form action="{{ route('exports.destroy', $job->id) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Hapus histori ini? (File zip juga akan dihapus)')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-400 hover:bg-rose-500 hover:text-white rounded-lg transition-colors cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                                    <svg class="w-8 h-8 mx-auto mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                    <p class="text-sm">Belum ada histori pelaporan / export massal.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
