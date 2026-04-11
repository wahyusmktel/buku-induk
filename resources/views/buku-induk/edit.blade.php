@extends('layouts.app')

@section('title', 'Edit Buku Induk — ' . $siswa->nama)
@section('header_title', 'Edit Buku Induk')
@section('breadcrumb')
    <a href="{{ route('buku-induk.index') }}" class="hover:text-indigo-600">Buku Induk</a>
    <span class="text-slate-300 mx-1">/</span>
    <a href="{{ route('buku-induk.show', $siswa->nisn) }}" class="hover:text-indigo-600">{{ $siswa->nama }}</a>
    <span class="text-slate-300 mx-1">/</span>
    <span class="text-slate-800 font-semibold">Lengkapi Data</span>
@endsection

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ 
    section: localStorage.getItem('editBiTab_{{ $siswa->nisn }}') || 'identitas', 
    tinggalDengan: '{{ old('periodik.bertempat_tinggal_pada', $siswa->dataPeriodik->bertempat_tinggal_pada ?? '') }}',
    showWaliWarning: false,
    validateWali(e) {
        if (this.tinggalDengan === 'Bersama Wali') {
            const nama = document.querySelector('input[name=\'wali[nama]\']').value.trim();
            const hub = document.querySelector('input[name=\'wali[hubungan]\']').value.trim();
            const pdk = document.querySelector('input[name=\'wali[pendidikan]\']').value.trim();
            const pkj = document.querySelector('input[name=\'wali[pekerjaan]\']').value.trim();
            
            if (!nama || !hub || !pdk || !pkj) {
                e.preventDefault();
                this.section = 'orang_tua';
                this.showWaliWarning = true;
                setTimeout(() => {
                    if (!nama) document.querySelector('input[name=\'wali[nama]\']').focus();
                    else if (!hub) document.querySelector('input[name=\'wali[hubungan]\']').focus();
                    else if (!pdk) document.querySelector('input[name=\'wali[pendidikan]\']').focus();
                    else if (!pkj) document.querySelector('input[name=\'wali[pekerjaan]\']').focus();
                }, 150);
                setTimeout(() => this.showWaliWarning = false, 5000);
            }
        }
    }
}" x-init="$watch('section', val => localStorage.setItem('editBiTab_{{ $siswa->nisn }}', val))">

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-8 text-white">
            <h2 class="text-2xl font-extrabold tracking-tight">Lengkapi Buku Induk</h2>
            <p class="text-indigo-200 text-sm mt-1 font-medium">{{ $siswa->nama }} — NISN {{ $siswa->nisn }}</p>
            
            {{-- Progress Bar --}}
            @php
                $progressBg = $kelengkapan >= 80 ? 'from-emerald-400 to-teal-300' : ($kelengkapan >= 40 ? 'from-amber-400 to-yellow-300' : 'from-rose-400 to-pink-300');
                $emoji = $kelengkapan >= 100 ? '🏆' : ($kelengkapan >= 80 ? '🎉' : ($kelengkapan >= 40 ? '💪' : '🚀'));
                $motivasi = $kelengkapan >= 100 ? 'Sempurna! Semua data sudah terlengkapi. Anda luar biasa!'
                    : ($kelengkapan >= 80 ? 'Hampir sampai! Sedikit lagi menuju data yang sempurna.'
                    : ($kelengkapan >= 40 ? 'Progres bagus! Terus lengkapi, setiap field yang terisi memperkuat arsip digital sekolah.'
                    : 'Mari mulai! Setiap data yang dilengkapi adalah langkah kecil menuju administrasi sekolah yang profesional.'));
            @endphp
            <div class="mt-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-white/60 uppercase tracking-widest">Kelengkapan Data</span>
                    <span class="text-sm font-black text-white">{{ $kelengkapan }}%</span>
                </div>
                <div class="w-full h-3 bg-white/20 rounded-full overflow-hidden backdrop-blur-sm">
                    <div class="h-full rounded-full bg-gradient-to-r {{ $progressBg }} transition-all duration-700" style="width: {{ $kelengkapan }}%"></div>
                </div>
                <div class="mt-3 flex items-start gap-3 px-4 py-3 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20">
                    <span class="text-xl flex-shrink-0">{{ $emoji }}</span>
                    <p class="text-sm font-semibold text-white/90">{{ $motivasi }}</p>
                </div>
            </div>
        </div>

        {{-- Section Nav --}}
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 flex gap-1 overflow-x-auto whitespace-nowrap">
            @foreach([
                'identitas' => 'Identitas Murid', 
                'orang_tua' => 'Data Orang Tua', 
                'periodik' => 'Data Periodik',
                'pendidikan' => 'Pendidikan Sebelumnya',
                'jasmani' => 'Keadaan Jasmani',
                'beasiswa' => 'Beasiswa',
                'registrasi' => 'Meninggalkan Sekolah',
                'photo' => 'Foto Siswa',
                'akademik' => 'Prestasi Akademik'
            ] as $key => $label)
            <button type="button" @click="section = '{{ $key }}'"
                    :class="section === '{{ $key }}' ? 'border-b-2 border-indigo-600 text-indigo-700 font-black' : 'text-slate-500 hover:text-slate-700'"
                    class="px-5 py-4 text-[13px] font-bold transition-all cursor-pointer tracking-wide uppercase">
                {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- DATALISTS FOR AUTOCOMPLETE / LIVE SEARCH --}}
        <datalist id="pekerjaan_list">
            @foreach(["Petani", "Nelayan", "Pedagang", "Wiraswasta", "Buruh Harian Lepas", "PNS", "TNI", "Polri", "Pegawai Swasta", "Guru", "Dosen", "Dokter", "Perawat", "Bidan", "Apoteker", "Seniman", "Penjahit", "Sopir", "Tukang Kayu", "Tukang Batu", "Tukang Cukur", "Montir", "Wartawan", "Pengacara", "Arsitek", "Akuntan", "Konsultan", "Peneliti", "Programmer", "Desainer", "Fotografer", "Blogger / Youtuber", "Driver Ojek Online", "Kurir", "Satpam / Resepsionis", "Pramusaji", "Chef / Koki", "Pelaut", "Pilot", "Pramugari", "Penambang", "Mandor", "Pensiunan", "Tidak Bekerja", "Ibu Rumah Tangga", "Tenaga Honorer", "Tukang Ojek Pangkalan", "Tukang Becak", "Pedagang Keliling", "Peternak", "Buruh Pabrik"] as $pek)
            <option value="{{ $pek }}">
            @endforeach
        </datalist>

        <datalist id="pendidikan_list">
            @foreach(["Tidak Sekolah", "SD/Sederajat", "SMP/Sederajat", "SMA/Sederajat", "D1", "D2", "D3", "D4", "S1", "S2", "S3"] as $pend)
            <option value="{{ $pend }}">
            @endforeach
        </datalist>

        <datalist id="bahasa_list">
            @foreach(["Indonesia", "Jawa", "Sunda", "Madura", "Batak", "Minangkabau", "Bugis", "Aceh", "Bali", "Betawi", "Banjar", "Melayu", "Dayak", "Sasak", "Makassar", "Inggris", "Arab", "Mandarin", "Jepang"] as $bhs)
            <option value="{{ $bhs }}">
            @endforeach
        </datalist>

        <form action="{{ route('buku-induk.update', $siswa->nisn) }}" method="POST" enctype="multipart/form-data" class="p-8" @submit="validateWali($event)">
            @csrf
            @method('PUT')

            {{-- TOAST SUCCESS --}}
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-5"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-5"
                 class="fixed z-50 bottom-8 right-8 bg-slate-800 text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3 font-semibold text-sm">
                <div class="flex-shrink-0 bg-emerald-500 rounded-full p-1.5">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p>{{ session('success') }}</p>
                <button type="button" @click="show = false" class="ml-4 text-slate-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @endif

            {{-- TOAST WALI WARNING --}}
            <div x-show="showWaliWarning" style="display: none;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-5"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-5"
                 class="fixed z-50 bottom-8 right-8 bg-slate-800 text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3 font-semibold text-sm max-w-sm">
                <div class="flex-shrink-0 bg-amber-500 rounded-full p-1.5 self-start mt-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <p class="leading-relaxed">Karena anda memilih tinggal bersama wali, silahkan isi data wali terlebih dahulu di tab Data Orang Tua.</p>
                <button type="button" @click="showWaliWarning = false" class="ml-2 self-start text-slate-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl">
                <p class="text-sm font-bold mb-2">Terjadi Kesalahan:</p>
                <ul class="list-disc list-inside text-xs space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- SECTION: IDENTITAS --}}
            <div x-show="section === 'identitas'" x-transition>
                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 mb-6 relative bg-slate-50/50">
                    <p class="absolute -top-3 left-6 px-2 bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-full border border-slate-200">Data Identitas Murid</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @php
                            $dt = $siswa->tanggal_lahir ? (\Carbon\Carbon::parse($siswa->tanggal_lahir)->format('Y-m-d')) : '';
                        @endphp
                        
                        <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">1. NIS</label><input type="text" name="nis" value="{{ old('nis', $siswa->nipd) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 font-bold text-slate-700"></div>
                        <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">2. NISN</label><input type="text" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 font-bold text-slate-700"></div>
                        <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">3. NIK</label><input type="text" name="nik" value="{{ old('nik', $siswa->nik) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 font-bold text-slate-700"></div>
                        <div class="space-y-1.5 lg:col-span-2"><label class="text-xs font-black text-slate-500 uppercase">4. Nama Lengkap Siswa</label><input type="text" name="nama" value="{{ old('nama', $siswa->nama) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 font-bold text-slate-700"></div>
                        <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">5. Nama Panggilan</label><input type="text" name="nama_panggilan" value="{{ old('nama_panggilan', $siswa->nama_panggilan) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 font-bold text-slate-700"></div>
                        
                        <div class="space-y-1.5">
                            <label class="text-xs font-black text-slate-500 uppercase">6. Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700">
                                @php $jk = old('jenis_kelamin', $siswa->jenis_kelamin ?? $siswa->jk); @endphp
                                <option value="L" {{ $jk == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ $jk == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        
                        <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">7a. Tempat Lahir</label><input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 font-bold text-slate-700"></div>
                        <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">7b. Tanggal Lahir</label><input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $dt) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 font-bold text-slate-700"></div>
                        
                        <div class="space-y-1.5">
                            <label class="text-xs font-black text-slate-500 uppercase">8. Agama</label>
                            <select name="agama" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700">
                                @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'] as $agm)
                                <option value="{{ $agm }}" {{ old('agama', $siswa->agama) == $agm ? 'selected' : '' }}>{{ $agm }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="space-y-1.5">
                            <label class="text-xs font-black text-slate-500 uppercase">9. Kewarganegaraan</label>
                            <select name="kewarganegaraan" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700">
                                @php $kwn = old('kewarganegaraan', $siswa->kewarganegaraan ?? 'WNI'); @endphp
                                <option value="WNI" {{ $kwn == 'WNI' || $kwn == 'Warga Negara Indonesia (WNI)' ? 'selected' : '' }}>Warga Negara Indonesia (WNI)</option>
                                <option value="WNA" {{ $kwn == 'WNA' || $kwn == 'Warga Negara Asing (WNA)' ? 'selected' : '' }}>Warga Negara Asing (WNA)</option>
                            </select>
                        </div>
                        
                        <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">10. Nomor Telepon</label><input type="tel" placeholder="08..." name="telepon" value="{{ old('telepon', $siswa->telepon ?? $siswa->nomor_telepon) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 font-bold text-slate-700"></div>
                    </div>
                </div>
            </div>

            {{-- SECTION: ORANG TUA --}}
            <div x-show="section === 'orang_tua'" x-transition>
                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 mb-6 relative bg-slate-50/50">
                    <p class="absolute -top-3 left-6 px-2 bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-full border border-slate-200">Data Orang Tua / Wali</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        @php
                            $ayah = $siswa->dataOrangTua ? $siswa->dataOrangTua->where('jenis', 'Ayah')->first() : null;
                            $ibu = $siswa->dataOrangTua ? $siswa->dataOrangTua->where('jenis', 'Ibu')->first() : null;
                            $wali = $siswa->dataOrangTua ? $siswa->dataOrangTua->where('jenis', 'Wali')->first() : null;
                        @endphp
                        
                        {{-- AYAH --}}
                        <div class="space-y-4">
                            <h4 class="font-black text-slate-700 border-b border-slate-100 pb-2 flex items-center gap-2"><span class="w-1 h-5 bg-blue-500 rounded-full"></span>Data Ayah</h4>
                            <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">1. Nama Lengkap Ayah</label><input type="text" name="ayah[nama]" value="{{ old('ayah.nama', $ayah->nama ?? $siswa->nama_ayah) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700"></div>
                            <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">2. Pendidikan Terakhir</label><input list="pendidikan_list" name="ayah[pendidikan]" value="{{ old('ayah.pendidikan', $ayah->pendidikan_terakhir ?? $siswa->jenjang_pendidikan_ayah) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700" placeholder="Pilih / ketik lainnya..."></div>
                            <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">3. Pekerjaan</label><input list="pekerjaan_list" name="ayah[pekerjaan]" value="{{ old('ayah.pekerjaan', $ayah->pekerjaan ?? $siswa->pekerjaan_ayah) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700" placeholder="Pilih / ketik lainnya..."></div>
                        </div>
                        
                        {{-- IBU --}}
                        <div class="space-y-4">
                            <h4 class="font-black text-slate-700 border-b border-slate-100 pb-2 flex items-center gap-2"><span class="w-1 h-5 bg-pink-500 rounded-full"></span>Data Ibu</h4>
                            <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">1. Nama Lengkap Ibu</label><input type="text" name="ibu[nama]" value="{{ old('ibu.nama', $ibu->nama ?? $siswa->nama_ibu) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700"></div>
                            <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">2. Pendidikan Terakhir</label><input list="pendidikan_list" name="ibu[pendidikan]" value="{{ old('ibu.pendidikan', $ibu->pendidikan_terakhir ?? $siswa->jenjang_pendidikan_ibu) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700" placeholder="Pilih / ketik lainnya..."></div>
                            <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">3. Pekerjaan</label><input list="pekerjaan_list" name="ibu[pekerjaan]" value="{{ old('ibu.pekerjaan', $ibu->pekerjaan ?? $siswa->pekerjaan_ibu) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700" placeholder="Pilih / ketik lainnya..."></div>
                        </div>
                    </div>

                    {{-- WALI --}}
                    <div class="mt-8 pt-8 border-t border-slate-200 border-dashed">
                        <div class="space-y-4">
                            <h4 class="font-black text-slate-700 border-b border-slate-100 pb-2 flex items-center gap-2">
                                <span class="w-1 h-5 bg-amber-500 rounded-full"></span>
                                <span x-text="tinggalDengan === 'Bersama Wali' ? 'Data Wali (Wajib Diisi)' : 'Data Wali (Jika Ada)'"></span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">1. Nama Wali</label><input type="text" name="wali[nama]" value="{{ old('wali.nama', $wali->nama ?? $siswa->nama_wali) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700"></div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-black text-slate-500 uppercase">2. Hubungan</label>
                                    <input list="hubungan_list" name="wali[hubungan]" value="{{ old('wali.hubungan', $wali->status_hubungan_wali ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700" placeholder="Kakek, dll...">
                                    <datalist id="hubungan_list"><option value="Kakek"><option value="Nenek"><option value="Paman"><option value="Bibi"><option value="Kakak"><option value="Kerabat Lainnya"></datalist>
                                </div>
                                <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">3. Pendidikan Wali</label><input list="pendidikan_list" name="wali[pendidikan]" value="{{ old('wali.pendidikan', $wali->pendidikan_terakhir ?? $siswa->jenjang_pendidikan_wali) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700" placeholder="Pilih..."></div>
                                <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">4. Pekerjaan Wali</label><input list="pekerjaan_list" name="wali[pekerjaan]" value="{{ old('wali.pekerjaan', $wali->pekerjaan ?? $siswa->pekerjaan_wali) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700" placeholder="Pilih..."></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION: PERIODIK --}}
            <div x-show="section === 'periodik'" x-transition>
                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 mb-6 relative bg-slate-50/50">
                    <p class="absolute -top-3 left-6 px-2 bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-full border border-slate-200">Data Periodik Siswa</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">1. Jumlah Saudara Kandung</label><input type="number" min="0" name="periodik[jml_saudara_kandung]" value="{{ old('periodik.jml_saudara_kandung', $siswa->dataPeriodik->jml_saudara_kandung ?? $siswa->jml_saudara_kandung ?? 0) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700"></div>
                        <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">2. Jumlah Saudara Tiri</label><input type="number" min="0" name="periodik[jml_saudara_tiri]" value="{{ old('periodik.jml_saudara_tiri', $siswa->dataPeriodik->jml_saudara_tiri ?? 0) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700"></div>
                        <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">3. Jumlah Saudara Angkat</label><input type="number" min="0" name="periodik[jml_saudara_angkat]" value="{{ old('periodik.jml_saudara_angkat', $siswa->dataPeriodik->jml_saudara_angkat ?? 0) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700"></div>
                        <div class="space-y-1.5 md:col-span-2 lg:col-span-3"><label class="text-xs font-black text-slate-500 uppercase">4. Bahasa Sehari-hari</label><input list="bahasa_list" name="periodik[bahasa_sehari_hari]" value="{{ old('periodik.bahasa_sehari_hari', $siswa->dataPeriodik->bahasa_sehari_hari ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700" placeholder="Pilih / ketik bahasa lainnya"></div>
                        <div class="space-y-1.5 md:col-span-2 lg:col-span-3"><label class="text-xs font-black text-slate-500 uppercase">5. Alamat Tinggal</label><textarea name="periodik[alamat_tinggal]" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700">{{ old('periodik.alamat_tinggal', $siswa->dataPeriodik->alamat_tinggal ?? $siswa->alamat) }}</textarea></div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-black text-slate-500 uppercase">6. Bertempat Tinggal Pada</label>
                            <select name="periodik[bertempat_tinggal_pada]" x-model="tinggalDengan" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700">
                                <option value="">-- Pilih --</option>
                                @foreach(['Bersama Orang Tua', 'Bersama Wali', 'Kos', 'Asrama', 'Panti Asuhan'] as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">7. Jarak Tempat Tinggal ke Sekolah (km)</label><input type="number" step="0.1" name="periodik[jarak]" value="{{ old('periodik.jarak', $siswa->dataPeriodik->jarak_tempat_tinggal_ke_sekolah ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700" placeholder="Misal: 2.5"></div>
                    </div>
                </div>
            </div>

            {{-- SECTION: PENDIDIKAN SEBELUMNYA --}}
            <div x-show="section === 'pendidikan'" x-transition>
                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 mb-6 relative bg-slate-50/50">
                    <p class="absolute -top-3 left-6 px-2 bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-full border border-slate-200">Data Pendidikan Sebelumnya</p>

                    {{-- A. Masuk Sebagai Siswa Baru --}}
                    <div class="mb-8">
                        <h4 class="font-black text-slate-700 border-b border-slate-200 pb-2 flex items-center gap-2 mb-4"><span class="w-1 h-5 bg-emerald-500 rounded-full"></span>A. Masuk Menjadi Siswa Baru Kelas</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-xs font-black text-slate-500 uppercase">1. Asal Siswa</label>
                                <select name="pendidikan_sebelumnya[asal_siswa]" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700">
                                    <option value="">-- Pilih --</option>
                                    @foreach(['Siswa Baru', 'Pindahan'] as $opt)
                                    <option value="{{ $opt }}" {{ old('pendidikan_sebelumnya.asal_siswa', $bukuInduk->asal_masuk_sekolah) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-black text-slate-500 uppercase">
                                    2. {{ $jenjang === 'SD' ? 'Nama Taman Kanak-Kanak' : 'Nama Sekolah Jenjang Sebelumnya' }}
                                </label>
                                <input type="text" name="pendidikan_sebelumnya[nama_sekolah_asal]" value="{{ old('pendidikan_sebelumnya.nama_sekolah_asal', $bukuInduk->nama_tk_asal ?? $siswa->sekolah_asal) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700" placeholder="{{ $jenjang === 'SD' ? 'Nama TK / PAUD...' : 'Nama sekolah sebelumnya...' }}">
                            </div>
                            <div class="space-y-1.5 lg:col-span-1">
                                <label class="text-xs font-black text-slate-500 uppercase">3. Alamat Sekolah Asal</label>
                                <input type="text" name="pendidikan_sebelumnya[alamat_sekolah_asal]" value="{{ old('pendidikan_sebelumnya.alamat_sekolah_asal', '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700" placeholder="Alamat sekolah...">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-black text-slate-500 uppercase">4. Tanggal Masuk Sekolah Ini</label>
                                <input type="date" name="pendidikan_sebelumnya[tgl_masuk]" value="{{ old('pendidikan_sebelumnya.tgl_masuk', $bukuInduk->tgl_masuk_sekolah ? $bukuInduk->tgl_masuk_sekolah->format('Y-m-d') : '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700">
                            </div>
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-xs font-black text-slate-500 uppercase">5. Keterangan</label>
                                <input type="text" name="pendidikan_sebelumnya[keterangan_masuk]" value="{{ old('pendidikan_sebelumnya.keterangan_masuk', '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700" placeholder="Keterangan tambahan...">
                            </div>
                        </div>
                    </div>

                    {{-- B. Pindahan Dari Sekolah Lain --}}
                    <div class="border-t border-dashed border-slate-200 pt-8">
                        <h4 class="font-black text-slate-700 border-b border-slate-200 pb-2 flex items-center gap-2 mb-4"><span class="w-1 h-5 bg-amber-500 rounded-full"></span>B. Pindahan Dari Sekolah Lain</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-xs font-black text-slate-500 uppercase">1. Nama Sekolah Asal</label>
                                <input type="text" name="pendidikan_sebelumnya[pindah_nama_sekolah]" value="{{ old('pendidikan_sebelumnya.pindah_nama_sekolah', $bukuInduk->pindah_dari) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700" placeholder="Nama SD / SMP / SMA asal...">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-black text-slate-500 uppercase">2. Dari Kelas</label>
                                <select name="pendidikan_sebelumnya[pindah_dari_kelas]" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700">
                                    <option value="">-- Pilih --</option>
                                    @php
                                        $kelasRange = match($jenjang) {
                                            'SMP' => range(7, 9),
                                            'SMA/SMK' => range(10, 12),
                                            default => range(1, 6),
                                        };
                                    @endphp
                                    @foreach($kelasRange as $k)
                                    <option value="{{ $k }}" {{ old('pendidikan_sebelumnya.pindah_dari_kelas') == $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-black text-slate-500 uppercase">3. Diterima pada Tanggal</label>
                                <input type="date" name="pendidikan_sebelumnya[pindah_tgl_diterima]" value="{{ old('pendidikan_sebelumnya.pindah_tgl_diterima', $bukuInduk->tgl_pindah_masuk ? $bukuInduk->tgl_pindah_masuk->format('Y-m-d') : '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700">
                            </div>
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-xs font-black text-slate-500 uppercase">4. Diterima di Kelas</label>
                                <select name="pendidikan_sebelumnya[pindah_di_kelas]" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700">
                                    <option value="">-- Pilih --</option>
                                    @foreach($kelasRange as $k)
                                    <option value="{{ $k }}" {{ old('pendidikan_sebelumnya.pindah_di_kelas', $bukuInduk->kelas_pindah_masuk) == $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION: JASMANI --}}
            <div x-show="section === 'jasmani'" x-transition>
                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 mb-6 relative bg-slate-50/50">
                    <p class="absolute -top-3 left-6 px-2 bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-full border border-slate-200">Data Keadaan Jasmani</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">1. Berat Badan (kg)</label><input type="number" step="0.1" name="jasmani[berat_badan]" value="{{ old('jasmani.berat_badan', $siswa->keadaanJasmani->berat_badan ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700"></div>
                        <div class="space-y-1.5"><label class="text-xs font-black text-slate-500 uppercase">2. Tinggi Badan (cm)</label><input type="number" step="0.1" name="jasmani[tinggi_badan]" value="{{ old('jasmani.tinggi_badan', $siswa->keadaanJasmani->tinggi_badan ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700"></div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-black text-slate-500 uppercase">3. Golongan Darah</label>
                            <select name="jasmani[golongan_darah]" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700">
                                <option value="">— Tidak Tahu —</option>
                                @foreach(['A', 'B', 'AB', 'O', 'A+', 'B+', 'AB+', 'O+'] as $gol)
                                <option value="{{ $gol }}" {{ old('jasmani.golongan_darah', $siswa->keadaanJasmani->golongan_darah ?? '') == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5 md:col-span-2 lg:col-span-3"><label class="text-xs font-black text-slate-500 uppercase">4. Nama Riwayat Penyakit</label><textarea name="jasmani[riwayat_penyakit]" rows="2" placeholder="Sebutkan jika ada..." class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700">{{ old('jasmani.riwayat_penyakit', $siswa->keadaanJasmani->nama_riwayat_penyakit ?? '') }}</textarea></div>
                        <div class="space-y-1.5 md:col-span-2 lg:col-span-3"><label class="text-xs font-black text-slate-500 uppercase">5. Kelainan Jasmani Siswa</label><textarea name="jasmani[kelainan]" rows="2" placeholder="Sebutkan jika ada..." class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700">{{ old('jasmani.kelainan', $siswa->keadaanJasmani->kelainan_jasmani ?? '') }}</textarea></div>
                    </div>
                </div>
            </div>

            {{-- SECTION: BEASISWA --}}
            <div x-show="section === 'beasiswa'" x-transition x-data="{ items: {{ $siswa->beasiswa->count() > 0 ? Js::from($siswa->beasiswa) : '[{}]' }} }">
                <div class="space-y-4">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="p-6 border border-slate-200 rounded-2xl bg-white relative">
                            <button type="button" @click="items.splice(index, 1)" class="absolute top-4 right-4 p-2 text-rose-500 hover:bg-rose-50 rounded-full" title="Hapus"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            <h5 class="font-bold text-slate-600 mb-4" x-text="'Catatan Beasiswa #' + (index + 1)"></h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5"><label class="text-[10px] font-black text-slate-500 uppercase">Jenis Beasiswa</label><input type="text" :name="'beasiswa['+index+'][jenis_beasiswa]'" x-model="item.jenis_beasiswa" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-sm text-slate-700" placeholder="Misal: PIP"></div>
                                <div class="space-y-1.5"><label class="text-[10px] font-black text-slate-500 uppercase">Sumber Beasiswa</label><input type="text" :name="'beasiswa['+index+'][sumber_beasiswa]'" x-model="item.sumber_beasiswa" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-sm text-slate-700" placeholder="Misal: Pemerintah Pusat"></div>
                                <div class="space-y-1.5"><label class="text-[10px] font-black text-slate-500 uppercase">Tahun Mulai</label><input type="number" :name="'beasiswa['+index+'][tahun_mulai]'" x-model="item.tahun_mulai" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-sm text-slate-700" placeholder="YYYY"></div>
                                <div class="space-y-1.5"><label class="text-[10px] font-black text-slate-500 uppercase">Tahun Selesai</label><input type="number" :name="'beasiswa['+index+'][tahun_selesai]'" x-model="item.tahun_selesai" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-sm text-slate-700" placeholder="YYYY (Optional)"></div>
                            </div>
                        </div>
                    </template>
                    <button type="button" @click="items.push({})" class="w-full py-3 border-2 border-dashed border-indigo-200 text-indigo-600 font-bold rounded-2xl hover:bg-indigo-50 transition-colors flex items-center justify-center gap-2">+ Tambah Riwayat Beasiswa</button>
                </div>
            </div>

            {{-- SECTION: MENINGGALKAN SEKOLAH --}}
            <div x-show="section === 'registrasi'" x-transition x-data="{ items: {{ $siswa->registrasi->count() > 0 ? Js::from($siswa->registrasi) : '[{}]' }} }">
                <div class="space-y-4">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="p-6 border border-slate-200 rounded-2xl bg-white relative">
                            <button type="button" @click="items.splice(index, 1)" class="absolute top-4 right-4 p-2 text-rose-500 hover:bg-rose-50 rounded-full" title="Hapus"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            <h5 class="font-bold text-slate-600 mb-4" x-text="'Catatan Registrasi #' + (index + 1)"></h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5"><label class="text-[10px] font-black text-slate-500 uppercase">Jenis Registrasi</label>
                                    <select :name="'registrasi['+index+'][jenis_registrasi]'" x-model="item.jenis_registrasi" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-sm text-slate-700">
                                        <option value="">-- Pilih --</option><option value="Tamat Belajar">Tamat Belajar</option><option value="Pindah Sekolah">Pindah Sekolah</option><option value="Keluar Sekolah">Keluar Sekolah</option><option value="Lain-lain">Lain-lain</option>
                                    </select>
                                </div>
                                <div class="space-y-1.5"><label class="text-[10px] font-black text-slate-500 uppercase">Tanggal</label><input type="date" :name="'registrasi['+index+'][tanggal]'" x-model="item.tanggal" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-sm text-slate-700"></div>
                                <div class="space-y-1.5 md:col-span-2"><label class="text-[10px] font-black text-slate-500 uppercase">Keterangan / Alasan</label><input type="text" :name="'registrasi['+index+'][keterangan]'" x-model="item.keterangan" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-sm text-slate-700" placeholder="Misal: Pindah ke SD N 1..."></div>
                            </div>
                        </div>
                    </template>
                    <button type="button" @click="items.push({})" class="w-full py-3 border-2 border-dashed border-rose-200 text-rose-600 font-bold rounded-2xl hover:bg-rose-50 transition-colors flex items-center justify-center gap-2">+ Tambah Catatan Keluar / Registrasi</button>
                </div>
            </div>
            {{-- SECTION: FOTO SISWA --}}
            <div x-show="section === 'photo'" x-transition x-data="{ 
                preview1: '{{ $bukuInduk->foto_1 ? Storage::url($bukuInduk->foto_1) : '' }}',
                preview2: '{{ $bukuInduk->foto_2 ? Storage::url($bukuInduk->foto_2) : '' }}'
            }">
                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 mb-6 relative bg-slate-50/50">
                    <p class="absolute -top-3 left-6 px-2 bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-full border border-slate-200">Pas Photo Siswa</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Photo 1 --}}
                        <div class="space-y-4">
                            <label class="block text-sm font-bold text-slate-700">Pas Photo 1 (Wajib Buku Induk)</label>
                            <div class="relative group">
                                <div class="w-48 h-64 mx-auto rounded-2xl border-2 border-dashed border-slate-200 bg-white flex items-center justify-center overflow-hidden transition-all group-hover:border-indigo-300">
                                    <template x-if="preview1">
                                        <img :src="preview1" class="w-full h-full object-cover">
                                    </template>
                                    <div x-show="!preview1" class="text-center p-4">
                                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">Belum ada foto</p>
                                    </div>
                                </div>
                            </div>
                            <input type="file" name="foto_1" accept="image/*" 
                                   @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { preview1 = e.target.result; }; reader.readAsDataURL(file); }"
                                   class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all">
                        </div>
                        {{-- Photo 2 --}}
                        <div class="space-y-4">
                            <label class="block text-sm font-bold text-slate-700">Pas Photo 2 (Arsip Sekolah)</label>
                            <div class="relative group">
                                <div class="w-48 h-64 mx-auto rounded-2xl border-2 border-dashed border-slate-200 bg-white flex items-center justify-center overflow-hidden transition-all group-hover:border-indigo-300">
                                    <template x-if="preview2">
                                        <img :src="preview2" class="w-full h-full object-cover">
                                    </template>
                                    <div x-show="!preview2" class="text-center p-4">
                                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">Belum ada foto</p>
                                    </div>
                                </div>
                            </div>
                            <input type="file" name="foto_2" accept="image/*" 
                                   @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { preview2 = e.target.result; }; reader.readAsDataURL(file); }"
                                   class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION: PRESTASI AKADEMIK --}}
            <div x-show="section === 'akademik'" x-transition>
                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 mb-6 relative bg-slate-50/50">
                    <p class="absolute -top-3 left-6 px-2 bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-full border border-slate-200">Prestasi Belajar</p>
                    <p class="text-sm text-slate-500 mb-6">Gunakan tombol di bawah untuk menambah atau memperbarui data nilai semester siswa.</p>
                    @hasanyrole('Super Admin|Operator|Tata Usaha')
                    <div class="flex flex-wrap gap-3">
                        <button type="button" x-on:click="$dispatch('open-prestasi-modal')"
                                class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-emerald-200 transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah / Update Nilai Semester
                        </button>
                        <button type="button" x-on:click="$dispatch('open-import-prestasi-modal')"
                                class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-bold rounded-2xl shadow-sm transition-all cursor-pointer">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Import dari Excel
                        </button>
                    </div>
                    @endhasanyrole
                </div>
            </div>
            
            {{-- Submit --}}
            <div class="mt-10 pt-6 border-t border-slate-100 flex gap-3">
                <a href="{{ route('buku-induk.show', $siswa->nisn) }}" class="flex-1 py-3 text-center text-sm font-bold text-slate-500 hover:bg-slate-100 rounded-2xl transition-all">Batal</a>
                <button type="submit" class="flex-[2] py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5 cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- MODAL: Input Prestasi (di luar form utama) --}}
    @hasanyrole('Super Admin|Operator|Tata Usaha')
    <div x-data="{ open: false }" @open-prestasi-modal.window="open = true"
         x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 text-white rounded-t-3xl flex items-center justify-between">
                <h3 class="text-xl font-extrabold tracking-tight">Input / Update Nilai Semester</h3>
                <button @click="open = false" class="p-2 hover:bg-white/10 rounded-full cursor-pointer"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form action="{{ route('prestasi.store', $siswa->nisn) }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Kelas</label>
                        <select name="kelas" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 font-bold text-slate-700">
                            @foreach(range(1,6) as $k)<option value="{{ $k }}">Kelas {{ $k }}</option>@endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Semester</label>
                        <select name="semester" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 font-bold text-slate-700">
                            <option value="1">Ganjil (1)</option><option value="2">Genap (2)</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Tahun Pelajaran</label>
                        <input type="text" name="tahun_pelajaran" placeholder="2024/2025" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 font-bold text-slate-700">
                    </div>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3">Nilai Mata Pelajaran</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
                        @foreach($mataPelajarans as $mapel)
                        <div class="space-y-1">
                            <label class="text-[0.65rem] font-bold text-slate-400">{{ $mapel->nama }}</label>
                            <input type="number" name="nilai[{{ $mapel->id }}]" min="0" max="100" step="0.5" placeholder="—"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm">
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Peringkat</label><input type="number" name="peringkat" min="1" placeholder="—" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"></div>
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Sakit (hari)</label><input type="number" name="hadir_sakit" min="0" value="0" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"></div>
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Izin (hari)</label><input type="number" name="hadir_izin" min="0" value="0" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"></div>
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Alpha (hari)</label><input type="number" name="hadir_alpha" min="0" value="0" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Sikap</label>
                        <select name="sikap" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"><option value="">—</option><option>Baik</option><option>Cukup</option><option>Kurang</option></select></div>
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Kerajinan</label>
                        <select name="kerajinan" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"><option value="">—</option><option>Baik</option><option>Cukup</option><option>Kurang</option></select></div>
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Kebersihan</label>
                        <select name="kebersihan_kerapian" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"><option value="">—</option><option>Baik</option><option>Cukup</option><option>Kurang</option></select></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Kenaikan Kelas</label>
                        <select name="keterangan_kenaikan" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"><option value="">—</option><option>Naik</option><option>Tidak Naik</option></select></div>
                    <div class="space-y-1.5"><label class="text-[0.65rem] font-bold text-slate-400 uppercase">Tgl Keputusan</label>
                        <input type="date" name="tgl_keputusan_kenaikan" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold text-slate-700 text-sm"></div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="open = false" class="flex-1 py-3 text-sm font-bold text-slate-500 hover:bg-slate-100 rounded-2xl transition-all cursor-pointer">Batal</button>
                    <button type="submit" class="flex-[2] py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-lg transition-all cursor-pointer">Simpan Nilai</button>
                </div>
            </form>
        </div>
    </div>
    @endhasanyrole

    {{-- MODAL: Import Prestasi --}}
    @hasanyrole('Super Admin|Operator|Tata Usaha')
    <div x-data="{ open: false }" @open-import-prestasi-modal.window="open = true"
         x-show="open" x-transition class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden">
            <div class="bg-emerald-600 px-8 py-6 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/10 rounded-xl">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold tracking-tight">Import Nilai Excel</h3>
                        <p class="text-xs text-white/70 font-bold uppercase tracking-widest">Update massal nilai semester</p>
                    </div>
                </div>
                <button @click="open = false" class="p-2 hover:bg-white/10 rounded-full cursor-pointer"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-8 space-y-6">
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 flex items-start gap-4">
                    <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm font-bold text-emerald-900">Gunakan Template Resmi</p>
                        <p class="text-xs text-emerald-700 leading-relaxed font-medium">Pastikan format kolom sesuai dengan template agar data berhasil diimpor.</p>
                        <a href="{{ route('prestasi.template') }}" class="inline-flex items-center gap-1.5 text-emerald-600 text-xs font-black hover:text-emerald-800 transition-colors mt-2">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Unduh Template Format Excel
                        </a>
                    </div>
                </div>
                <form action="{{ route('prestasi.import', $siswa->nisn) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest px-1">Pilih File Excel</label>
                        <input type="file" name="file" required accept=".xlsx,.xls,.csv"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 font-bold text-sm cursor-pointer">
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="open = false" class="flex-1 py-3.5 text-sm font-bold text-slate-500 hover:bg-slate-100 rounded-2xl transition-all cursor-pointer">Batal</button>
                        <button type="submit" class="flex-[2] py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-emerald-200 transition-all cursor-pointer">Unggah & Proses Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endhasanyrole

</div>
@endsection
