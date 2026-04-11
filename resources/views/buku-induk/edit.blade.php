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
        </div>

        {{-- Section Nav --}}
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 flex gap-1 overflow-x-auto whitespace-nowrap">
            @foreach([
                'identitas' => 'Identitas Murid', 
                'orang_tua' => 'Data Orang Tua', 
                'periodik' => 'Data Periodik',
                'jasmani' => 'Keadaan Jasmani',
                'beasiswa' => 'Beasiswa',
                'registrasi' => 'Meninggalkan Sekolah'
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

        <form action="{{ route('buku-induk.update', $siswa->nisn) }}" method="POST" class="p-8" @submit="validateWali($event)">
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
            
            {{-- Submit --}}
            <div class="mt-10 pt-6 border-t border-slate-100 flex gap-3">
                <a href="{{ route('buku-induk.show', $siswa->nisn) }}" class="flex-1 py-3 text-center text-sm font-bold text-slate-500 hover:bg-slate-100 rounded-2xl transition-all">Batal</a>
                <button type="submit" class="flex-[2] py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5 cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
