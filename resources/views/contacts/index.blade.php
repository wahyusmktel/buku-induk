@extends('layouts.app')

@section('title', 'Pesan Masuk (Inbox)')
@section('header_title', 'Pesan Masuk')
@section('breadcrumb', 'Inbox')

@section('content')
<div class="space-y-5" x-data="{ selectedMessage: null }">

    {{-- ── Page Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Pesan Masuk</h2>
            <p class="text-xs text-slate-400 mt-0.5">
                Kelola pesan dan pertanyaan dari pengunjung halaman landing.
            </p>
        </div>
        <div class="flex gap-2 bg-white px-3 py-1.5 rounded-lg border border-slate-200">
            <div class="flex items-center gap-1.5 text-xs font-bold text-slate-600">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span> 
                {{ \App\Models\ContactMessage::where('is_read', false)->count() }} Belum Dibaca
            </div>
        </div>
    </div>

    {{-- ── Data Table ── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($messages->isEmpty())
        <div class="py-20 text-center px-6">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200"
                 style="background:rgba(12,74,110,.05)">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-sm font-bold text-slate-700">Tidak Ada Pesan</h3>
            <p class="text-xs text-slate-400 mt-1">Belum ada pesan yang masuk dari pengunjung.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:linear-gradient(135deg, #0c4a6e 0%, #0369a1 100%);">
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100" style="width:180px">Pengirim</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100">Isi Pesan</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-sky-100" style="width:150px">Waktu</th>
                        <th class="py-3.5 px-4 text-center text-[0.62rem] font-bold uppercase tracking-widest text-sky-100" style="width:180px">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($messages as $msg)
                    <tr class="hover:bg-slate-50 transition-colors {{ !$msg->is_read ? 'bg-indigo-50/30' : '' }}">
                        <td class="py-4 px-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800 {{ !$msg->is_read ? 'text-indigo-700' : '' }}">
                                    {{ $msg->name }}
                                    @if(!$msg->is_read)
                                        <span class="inline-block w-2 h-2 rounded-full bg-indigo-500 ml-1"></span>
                                    @endif
                                </span>
                                <span class="text-xs text-slate-500">{{ $msg->email }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <p class="text-slate-600 line-clamp-2 text-xs leading-relaxed">
                                {{ $msg->message }}
                            </p>
                        </td>
                        <td class="py-4 px-4 text-xs text-slate-400 font-medium">
                            {{ $msg->created_at->diffForHumans() }}
                        </td>
                        <td class="py-4 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="selectedMessage = @js($msg)"
                                        class="p-2 bg-sky-50 text-sky-600 rounded-lg hover:bg-sky-600 hover:text-white transition-all border border-sky-100">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z"/></svg>
                                </button>

                                @if(!$msg->is_read)
                                <form action="{{ route('contacts.read', $msg->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all border border-emerald-100">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('contacts.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all border border-rose-100">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 bg-slate-50 border-t border-slate-100">
            {{ $messages->links() }}
        </div>
        @endif
    </div>

    {{-- ── Message Detail Modal ── --}}
    <div x-show="selectedMessage" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
         x-cloak x-transition>
        <div class="bg-white rounded-3xl w-full max-w-xl shadow-2xl overflow-hidden border border-white/20"
             @click.away="selectedMessage = null">
            <div class="bg-indigo-600 p-6 text-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center font-bold">
                        <span x-text="selectedMessage ? selectedMessage.name.substring(0,2).toUpperCase() : ''"></span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg leading-tight" x-text="selectedMessage?.name"></h3>
                        <p class="text-indigo-100 text-xs" x-text="selectedMessage?.email"></p>
                    </div>
                </div>
                <button @click="selectedMessage = null" class="p-2 hover:bg-white/10 rounded-full transition-colors">
                    <svg class="w-5 h-5 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-8">
                <div class="mb-6">
                    <label class="block text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest mb-1">Diterima Pada</label>
                    <p class="text-sm font-semibold text-slate-700" x-text="selectedMessage ? new Date(selectedMessage.created_at).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' }) : ''"></p>
                </div>
                <div>
                    <label class="block text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest mb-2">Isi Pesan</label>
                    <div class="bg-slate-50 border border-slate-100 p-5 rounded-2xl text-slate-600 text-sm leading-relaxed whitespace-pre-wrap min-h-[150px]" x-text="selectedMessage?.message"></div>
                </div>
            </div>
            <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3">
                <a :href="'mailto:' + selectedMessage?.email" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-indigo-200">
                    Balas via Email
                </a>
                <button @click="selectedMessage = null" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-600 text-xs font-bold rounded-xl transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
