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
<div class="max-w-5xl mx-auto" x-data="{ section: localStorage.getItem('editBiTab_{{ $siswa->nisn }}') || 'identitas' }" x-init="$watch('section', val => localStorage.setItem('editBiTab_{{ $siswa->nisn }}', val))">

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-8 text-white">
            <h2 class="text-2xl font-extrabold tracking-tight">Lengkapi Buku Induk</h2>
            <p class="text-indigo-200 text-sm mt-1 font-medium">{{ $siswa->nama }} — NISN {{ $siswa->nisn }}</p>
        </div>

        {{-- Section Nav --}}
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 flex gap-1 overflow-x-auto">
            @foreach(['identitas' => 'Identitas Murid', 'orang_tua' => 'Orang Tua / Wali'] as $key => $label)
            <button type="button" @click="section = '{{ $key }}'"
                    :class="section === '{{ $key }}' ? 'border-b-2 border-indigo-600 text-indigo-700 font-black' : 'text-slate-500 hover:text-slate-700'"
                    class="px-4 py-3.5 text-sm font-semibold transition-all whitespace-nowrap cursor-pointer">
                {{ $label }}
            </button>
            @endforeach
        </div>

        <form action="{{ route('buku-induk.update', $siswa->nisn) }}" method="POST" class="p-8">
            @csrf
            @method('PUT')

            @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl">
                <p class="text-sm font-bold mb-2">Terjadi Kesalahan:</p>
                <ul class="list-disc list-inside text-xs space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- SECTION: IDENTITAS --}}
            <div x-show="section === 'identitas'" x-transition>
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-6">Data Identitas Murid</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $dt = $siswa->tanggal_lahir ? (\Carbon\Carbon::parse($siswa->tanggal_lahir)->format('Y-m-d')) : '';
                        $fields = [
                            ['name' => 'nik', 'label' => 'NIK (KTP)', 'type' => 'text', 'value' => $siswa->nik],
                            ['name' => 'nama', 'label' => 'Nama Lengkap', 'type' => 'text', 'value' => $siswa->nama],
                            ['name' => 'nama_panggilan', 'label' => 'Nama Panggilan', 'type' => 'text', 'value' => $siswa->nama_panggilan],
                            ['name' => 'tempat_lahir', 'label' => 'Tempat Lahir', 'type' => 'text', 'value' => $siswa->tempat_lahir],
                            ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date', 'value' => $dt],
                            ['name' => 'telepon', 'label' => 'Nomor Telepon', 'type' => 'text', 'value' => $siswa->telepon],
                        ];
                    @endphp
                    @foreach($fields as $field)
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ $field['label'] }}</label>
                        <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" value="{{ old($field['name'], $field['value']) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-700">
                    </div>
                    @endforeach

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Agama</label>
                        <select name="agama" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700">
                            @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'] as $agm)
                            <option value="{{ $agm }}" {{ old('agama', $siswa->agama) == $agm ? 'selected' : '' }}>{{ $agm }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Jenis Kelamin</label>
                        <select name="jk" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700">
                             <option value="L" {{ old('jk', $siswa->jk) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                             <option value="P" {{ old('jk', $siswa->jk) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Golongan Darah</label>
                        <select name="golongan_darah" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                            <option value="">— Tidak Tahu —</option>
                            @foreach(['A', 'B', 'AB', 'O', 'A+', 'B+', 'AB+', 'O+'] as $gol)
                            <option value="{{ $gol }}" {{ old('golongan_darah', $siswa->keadaanJasmani->golongan_darah ?? '') == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Kewarganegaraan</label>
                        <select name="kewarganegaraan" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                            @php $kewarganegaraan = old('kewarganegaraan', $siswa->kewarganegaraan ?? 'WNI'); @endphp
                            <option value="WNI" {{ $kewarganegaraan == 'WNI' ? 'selected' : '' }}>WNI</option>
                            <option value="WNA" {{ $kewarganegaraan == 'WNA' ? 'selected' : '' }}>WNA</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Bahasa Sehari-hari</label>
                        <input type="text" name="bahasa_sehari_hari" value="{{ old('bahasa_sehari_hari', $siswa->dataPeriodik->bahasa_sehari_hari ?? '') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-700">
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Jml Saudara Kandung</label>
                        <input type="number" name="jml_saudara_kandung" min="0" value="{{ old('jml_saudara_kandung', $siswa->dataPeriodik->jml_saudara_kandung ?? $siswa->jml_saudara_kandung ?? 0) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Jml Saudara Tiri</label>
                        <input type="number" name="jml_saudara_tiri" min="0" value="{{ old('jml_saudara_tiri', $siswa->dataPeriodik->jml_saudara_tiri ?? 0) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Jml Saudara Angkat</label>
                        <input type="number" name="jml_saudara_angkat" min="0" value="{{ old('jml_saudara_angkat', $siswa->dataPeriodik->jml_saudara_angkat ?? 0) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                    </div>

                    <div class="md:col-span-2 lg:col-span-3 space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Alamat Tempat Tinggal</label>
                        <textarea name="alamat" rows="2"
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">{{ old('alamat', $siswa->dataPeriodik->alamat_tinggal ?? $siswa->alamat) }}</textarea>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Bertempat Tinggal Pada</label>
                        <select name="jenis_tinggal" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                            <option value="">— Pilih —</option>
                            @php
                                $tPada = old('jenis_tinggal', $siswa->dataPeriodik->bertempat_tinggal_pada ?? ($siswa->jenis_tinggal ? ucwords(str_replace('_', ' ', $siswa->jenis_tinggal)) : ''));
                            @endphp
                            @foreach(['Orang Tua', 'Wali', 'Asrama/Kos', 'Kerabat'] as $opt)
                            <option value="{{ $opt }}" {{ $tPada == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Jarak ke Sekolah (km)</label>
                        <input type="number" step="0.1" name="jarak_rumah_ke_sekolah_km" value="{{ old('jarak_rumah_ke_sekolah_km', $siswa->dataPeriodik->jarak_tempat_tinggal_ke_sekolah ?? $siswa->jarak_rumah_ke_sekolah_km ?? '') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 font-bold text-slate-700">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Berat Badan (kg)</label>
                        <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan', $siswa->keadaanJasmani->berat_badan ?? '') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 font-bold text-slate-700">
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan', $siswa->keadaanJasmani->tinggi_badan ?? '') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 font-bold text-slate-700">
                    </div>

                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Riwayat Penyakit Khusus</label>
                        <textarea name="riwayat_penyakit" rows="2" placeholder="Contoh: Tifus..."
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">{{ old('riwayat_penyakit', $siswa->keadaanJasmani->nama_riwayat_penyakit ?? '') }}</textarea>
                    </div>
                    
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Kelainan Jasmani / Berkebutuhan Khusus</label>
                        <textarea name="kebutuhan_khusus" rows="2" placeholder="Contoh: Tunanetra..."
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">{{ old('kebutuhan_khusus', $siswa->keadaanJasmani->kelainan_jasmani ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- SECTION: ORANG TUA --}}
            <div x-show="section === 'orang_tua'" x-transition>
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-6">Data Lengkap Orang Tua</p>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    @foreach(['ayah' => 'Ayah', 'ibu' => 'Ibu'] as $key => $label)
                    <div class="space-y-5">
                        <h4 class="font-black text-slate-700 border-b border-slate-100 pb-2 flex items-center gap-2">
                            <span class="w-1 h-5 {{ $key === 'ayah' ? 'bg-blue-500' : 'bg-pink-500' }} rounded-full"></span>
                            Data {{ $label }}
                        </h4>
                        @php
                            // Ambil data dari $siswa sebagai fallback jika $bukuInduk kosong
                            $namaKey = 'nama_' . $key;          // nama_ayah / nama_ibu
                            $tahunLahirKey = 'tahun_lahir_' . $key; // tahun_lahir_ayah / tahun_lahir_ibu
                            $pekerjaanKey = 'pekerjaan_' . $key;   // pekerjaan_ayah / pekerjaan_ibu
                            $pendidikanKey = 'jenjang_pendidikan_' . $key; // jenjang_pendidikan_ayah / jenjang_pendidikan_ibu

                            $pFields = [
                                'nama_' . $key => ['label' => 'Nama ' . $label, 'type' => 'text', 'siswa_key' => $namaKey],
                                'tempat_lahir_' . $key => ['label' => 'Tempat Lahir', 'type' => 'text', 'siswa_key' => null],
                                'tanggal_lahir_' . $key => ['label' => 'Tanggal Lahir', 'type' => 'date', 'siswa_key' => null, 'siswa_tahun' => $tahunLahirKey],
                                'agama_' . $key => ['label' => 'Agama', 'type' => 'select', 'options' => ['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya'], 'siswa_key' => null],
                                'pekerjaan_' . $key . '_bi' => ['label' => 'Pekerjaan', 'type' => 'text', 'siswa_key' => $pekerjaanKey],
                                'pendidikan_' . $key . '_bi' => ['label' => 'Pendidikan Terakhir', 'type' => 'text', 'siswa_key' => $pendidikanKey],
                                'kewarganegaraan_' . $key => ['label' => 'Kewarganegaraan', 'type' => 'select', 'options' => ['WNI', 'WNA'], 'siswa_key' => null],
                                'alamat_' . $key => ['label' => 'Alamat', 'type' => 'textarea', 'siswa_key' => null],
                            ];
                        @endphp
                        @foreach($pFields as $name => $meta)
                        <div class="space-y-1.5">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ $meta['label'] }}</label>
                            @php
                                // Tentukan nilai fallback: coba bukuInduk dulu, lalu siswa (jenis field)
                                if ($meta['type'] === 'date') {
                                    $rawValue = $bukuInduk->$name ?? null;
                                    $fieldVal = old($name, $rawValue ? $rawValue->format('Y-m-d') : '');
                                } elseif (isset($meta['siswa_key']) && $meta['siswa_key']) {
                                    $fieldVal = old($name, $bukuInduk->$name ?? $siswa->{$meta['siswa_key']});
                                } else {
                                    $fieldVal = old($name, $bukuInduk->$name);
                                }
                            @endphp
                            @if($meta['type'] === 'select')
                            <select name="{{ $name }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                                <option value="">— Pilih —</option>
                                @foreach($meta['options'] as $opt)
                                <option value="{{ $opt }}" {{ $fieldVal == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                            @elseif($meta['type'] === 'textarea')
                            <textarea name="{{ $name }}" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">{{ $fieldVal }}</textarea>
                            @else
                            <input type="{{ $meta['type'] }}" name="{{ $name }}" value="{{ $fieldVal }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>

                {{-- Wali --}}
                <div class="mt-10 pt-8 border-t border-slate-100">
                    <h4 class="font-black text-slate-700 mb-5 flex items-center gap-2"><span class="w-1 h-5 bg-amber-500 rounded-full"></span>Data Wali (jika ada)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @php
                            $waliFields = [
                                'nama_wali_bi'      => ['label' => 'Nama Wali',              'siswa_key' => 'nama_wali'],
                                'hubungan_wali'     => ['label' => 'Hubungan dengan Siswa',  'siswa_key' => null],
                                'pekerjaan_wali_bi' => ['label' => 'Pekerjaan',               'siswa_key' => 'pekerjaan_wali'],
                                'pendidikan_wali_bi'=> ['label' => 'Pendidikan Terakhir',     'siswa_key' => 'jenjang_pendidikan_wali'],
                                'telp_wali_bi'      => ['label' => 'No. Telepon',             'siswa_key' => null],
                            ];
                        @endphp
                        @foreach($waliFields as $name => $meta)
                        <div class="space-y-1.5">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ $meta['label'] }}</label>
                            <input type="text" name="{{ $name }}" value="{{ old($name, $bukuInduk->$name ?? ($meta['siswa_key'] ? $siswa->{$meta['siswa_key']} : '')) }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                        </div>
                        @endforeach
                        <div class="md:col-span-2 lg:col-span-3 space-y-1.5">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Alamat Wali</label>
                            <textarea name="alamat_wali_bi" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">{{ old('alamat_wali_bi', $bukuInduk->alamat_wali_bi) }}</textarea>
                        </div>
                    </div>
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
