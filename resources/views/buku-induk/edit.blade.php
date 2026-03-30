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
<div class="max-w-5xl mx-auto" x-data="{ section: 'identitas' }">

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-8 text-white">
            <h2 class="text-2xl font-extrabold tracking-tight">Lengkapi Buku Induk</h2>
            <p class="text-indigo-200 text-sm mt-1 font-medium">{{ $siswa->nama }} — NISN {{ $siswa->nisn }}</p>
        </div>

        {{-- Section Nav --}}
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 flex gap-1 overflow-x-auto">
            @foreach(['identitas' => 'Identitas', 'orang_tua' => 'Orang Tua', 'masuk_keluar' => 'Masuk/Keluar'] as $key => $label)
            <button @click="section = '{{ $key }}'"
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
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-6">Data Identitas Tambahan</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $fields = [
                            ['name' => 'no_induk', 'label' => 'Nomor Buku Induk', 'type' => 'text', 'value' => $bukuInduk->no_induk ?? $siswa->nipd],
                            ['name' => 'nama_panggilan', 'label' => 'Nama Panggilan', 'type' => 'text', 'value' => $bukuInduk->nama_panggilan ?? $siswa->nama_panggilan],
                            ['name' => 'bahasa_sehari_hari', 'label' => 'Bahasa Sehari-hari', 'type' => 'text', 'value' => $bukuInduk->bahasa_sehari_hari ?? $siswa->bahasa_sehari_hari, 'placeholder' => 'contoh: Indonesia'],
                        ];
                    @endphp
                    @foreach($fields as $field)
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ $field['label'] }}</label>
                        <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" value="{{ old($field['name'], $field['value']) }}"
                               placeholder="{{ $field['placeholder'] ?? '' }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-700">
                    </div>
                    @endforeach

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Golongan Darah</label>
                        <select name="golongan_darah" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                            <option value="">— Tidak Tahu —</option>
                            @foreach(['A', 'B', 'AB', 'O', 'A+', 'B+', 'AB+', 'O+'] as $gol)
                            <option value="{{ $gol }}" {{ old('golongan_darah', $bukuInduk->golongan_darah ?? $siswa->golongan_darah) == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Kewarganegaraan</label>
                        <select name="kewarganegaraan" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                            @php $kewarganegaraan = old('kewarganegaraan', $bukuInduk->kewarganegaraan ?? $siswa->kewarganegaraan ?? 'WNI'); @endphp
                            <option value="WNI" {{ $kewarganegaraan == 'WNI' ? 'selected' : '' }}>WNI</option>
                            <option value="WNA" {{ $kewarganegaraan == 'WNA' ? 'selected' : '' }}>WNA</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Bertempat Tinggal Dengan</label>
                        <select name="bertempat_tinggal_dengan" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                            <option value="">— Pilih —</option>
                            @php
                                $tinggalDengan = old('bertempat_tinggal_dengan', $bukuInduk->bertempat_tinggal_dengan ?? ($siswa->jenis_tinggal ? ucwords(str_replace('_', ' ', $siswa->jenis_tinggal)) : ''));
                            @endphp
                            @foreach(['Orang Tua', 'Wali', 'Asrama/Kos', 'Kerabat'] as $opt)
                            <option value="{{ $opt }}" {{ $tinggalDengan == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Jml Saudara Kandung</label>
                        <input type="number" name="jml_saudara_kandung" min="0" value="{{ old('jml_saudara_kandung', $siswa->jml_saudara_kandung ?? 0) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700 bg-slate-50" readonly title="Data dari Dapodik">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Jml Saudara Tiri</label>
                        <input type="number" name="jml_saudara_tiri" min="0" value="{{ old('jml_saudara_tiri', $bukuInduk->jml_saudara_tiri ?? '') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Jml Saudara Angkat</label>
                        <input type="number" name="jml_saudara_angkat" min="0" value="{{ old('jml_saudara_angkat', $bukuInduk->jml_saudara_angkat ?? '') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                    </div>

                    <div class="md:col-span-2 lg:col-span-3 space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Riwayat Penyakit Berat</label>
                        <textarea name="riwayat_penyakit" rows="3" placeholder="Contoh: Tifus (2022), Bronkitis..."
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">{{ old('riwayat_penyakit', $bukuInduk->riwayat_penyakit ?? $siswa->riwayat_penyakit) }}</textarea>
                    </div>

                    <div class="md:col-span-2 lg:col-span-3 space-y-1.5">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Catatan Beasiswa / PIP</label>
                        <textarea name="beasiswa" rows="3" placeholder="Contoh: PIP 2023, Beasiswa Daerah 2024..."
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">{{ old('beasiswa', $bukuInduk->beasiswa) }}</textarea>
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

            {{-- SECTION: MASUK / KELUAR --}}
            <div x-show="section === 'masuk_keluar'" x-transition>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    {{-- Masuk --}}
                    <div class="space-y-5">
                        <h4 class="font-black text-slate-700 border-b border-slate-100 pb-2 flex items-center gap-2"><span class="w-1 h-5 bg-sky-500 rounded-full"></span>Perkembangan Murid / Masuk</h4>

                        {{-- Sekolah Asal (dari Dapodik, readonly) --}}
                        @if($siswa->sekolah_asal)
                        <div class="space-y-1.5">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Sekolah Asal (Dapodik)</label>
                            <input type="text" value="{{ $siswa->sekolah_asal }}" disabled
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-400 cursor-not-allowed">
                        </div>
                        @endif

                        <div class="space-y-1.5">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Asal Masuk Sekolah</label>
                            <select name="asal_masuk_sekolah" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                                <option value="">— Pilih —</option>
                                @foreach(['TK/Paud', 'Rumah (Belum Sekolah)', 'SD Lain (Pindahan)'] as $opt)
                                <option value="{{ $opt }}" {{ old('asal_masuk_sekolah', $bukuInduk->asal_masuk_sekolah) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        @foreach(['nama_tk_asal' => ['Nama TK/Paud Asal', 'text'], 'tgl_masuk_sekolah' => ['Tanggal Masuk Sekolah', 'date'], 'pindah_dari' => ['Pindahan dari Sekolah', 'text'], 'kelas_pindah_masuk' => ['Masuk di Kelas', 'text'], 'tgl_pindah_masuk' => ['Tanggal Pindah Masuk', 'date']] as $name => [$label, $type])
                        <div class="space-y-1.5">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ $label }}</label>
                            <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $type === 'date' ? ($bukuInduk->$name?->format('Y-m-d') ?? '') : $bukuInduk->$name) }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                        </div>
                        @endforeach
                    </div>
                    {{-- Keluar / Lulus --}}
                    <div class="space-y-5">
                        <h4 class="font-black text-slate-700 border-b border-slate-100 pb-2 flex items-center gap-2"><span class="w-1 h-5 bg-emerald-500 rounded-full"></span>Tamat / Keluar</h4>
                        @foreach([
                            'tgl_lulus'     => ['Tanggal Lulus', 'date'],
                            'no_ijazah'     => ['Nomor Ijazah', 'text', 'no_seri_ijazah'],
                            'lanjut_ke'     => ['Melanjutkan ke Sekolah', 'text'],
                            'tgl_keluar'    => ['Tanggal Keluar', 'date'],
                            'alasan_keluar' => ['Alasan Keluar', 'text'],
                        ] as $name => $fieldMeta)
                        @php
                            [$lbl, $type] = $fieldMeta;
                            $siswaFallbackKey = $fieldMeta[2] ?? null;
                            if ($type === 'date') {
                                $val = old($name, $bukuInduk->$name?->format('Y-m-d') ?? '');
                            } elseif ($siswaFallbackKey) {
                                $val = old($name, $bukuInduk->$name ?? $siswa->$siswaFallbackKey);
                            } else {
                                $val = old($name, $bukuInduk->$name);
                            }
                        @endphp
                        <div class="space-y-1.5">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ $lbl }}</label>
                            <input type="{{ $type }}" name="{{ $name }}" value="{{ $val }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                        </div>
                        @endforeach
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
