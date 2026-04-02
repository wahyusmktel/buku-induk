@extends('layouts.app')

@section('title', 'Ubah Data Siswa: ' . $siswa->nama)
@section('header_title', 'Ubah Profil Siswa')
@section('breadcrumb')
    <a href="{{ route('siswas.index') }}" class="hover:text-sky-600 transition-colors">Data Pokok Siswa</a>
    <span class="text-slate-300 mx-1">/</span>
    <span class="text-slate-800 font-semibold italic">Edit Profil</span>
@endsection

@section('content')
<div class="max-w-6xl mx-auto pb-20">
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-10 py-12 text-white relative overflow-hidden">
             <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full -mr-20 -mt-20"></div>
             <div class="relative z-10">
                <h2 class="text-3xl font-black tracking-tight">Perbarui Profil Lengkap</h2>
                <p class="text-slate-400 text-sm mt-2 font-medium">Pastikan data yang diinput sesuai dengan dokumen resmi kesiswaan.</p>
             </div>
        </div>

        <form action="{{ route('siswas.update', $siswa) }}" method="POST" class="p-10 space-y-16">
            @csrf
            @method('PUT')

            {{-- 1. IDENTITAS INTI & BIODATA --}}
            <section class="space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-sky-100 text-sky-600 rounded-xl flex items-center justify-center font-black">01</div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Identitas Inti & Biodata</h3>
                    <div class="h-px bg-slate-100 flex-1 ml-4"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap Siswa</label>
                        <input type="text" name="nama" value="{{ old('nama', $siswa->nama) }}" required class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all font-bold text-slate-700">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Panggilan</label>
                        <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan', $siswa->nama_panggilan) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all font-bold text-slate-700">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">NISN</label>
                        <input type="text" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all font-mono font-bold text-slate-700">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">NIPD</label>
                        <input type="text" name="nipd" value="{{ old('nipd', $siswa->nipd) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all font-mono font-bold text-slate-700">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">NIK (KTP)</label>
                        <input type="text" name="nik" value="{{ old('nik', $siswa->nik) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all font-mono font-bold text-slate-700">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">No. Kartu Keluarga</label>
                        <input type="text" name="no_kk" value="{{ old('no_kk', $siswa->no_kk) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all font-mono font-bold text-slate-700">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">No. Reg Akta Lahir</label>
                        <input type="text" name="no_registrasi_akta_lahir" value="{{ old('no_registrasi_akta_lahir', $siswa->no_registrasi_akta_lahir) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all font-mono font-bold text-slate-700">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                        <select name="jk" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all font-bold text-slate-700 appearance-none bg-white">
                            <option value="L" {{ old('jk', $siswa->jk) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jk', $siswa->jk) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Agama</label>
                        <input type="text" name="agama" value="{{ old('agama', $siswa->agama) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all font-bold text-slate-700">
                    </div>
                </div>
            </section>

            {{-- 2. KELAHIRAN & ALAMAT --}}
            <section class="space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center font-black">02</div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Kelahiran & Alamat</h3>
                    <div class="h-px bg-slate-100 flex-1 ml-4"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-700">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('Y-m-d') : '') }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-700">
                    </div>
                    
                    <div class="md:col-span-3 space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Jalan</label>
                        <input type="text" name="alamat" value="{{ old('alamat', $siswa->alamat) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-700">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">RT / RW</label>
                        <div class="flex gap-2">
                             <input type="text" name="rt" placeholder="RT" value="{{ old('rt', $siswa->rt) }}" class="w-1/2 px-4 py-3.5 rounded-xl border border-slate-200 text-center font-bold text-slate-700">
                             <input type="text" name="rw" placeholder="RW" value="{{ old('rw', $siswa->rw) }}" class="w-1/2 px-4 py-3.5 rounded-xl border border-slate-200 text-center font-bold text-slate-700">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Dusun / Lingkungan</label>
                        <input type="text" name="dusun" value="{{ old('dusun', $siswa->dusun) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 text-sm font-bold text-slate-700">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kelurahan / Desa</label>
                        <input type="text" name="kelurahan" value="{{ old('kelurahan', $siswa->kelurahan) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 text-sm font-bold text-slate-700">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan', $siswa->kecamatan) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 text-sm font-bold text-slate-700">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kode Pos</label>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos', $siswa->kode_pos) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 text-sm font-bold text-slate-700">
                    </div>
                </div>
            </section>

            {{-- 3. KONTAK & LOKASI --}}
            <section class="space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center font-black">03</div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Kontak & Lokasi</h3>
                    <div class="h-px bg-slate-100 flex-1 ml-4"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-slate-700">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">No HP / WhatsApp</label>
                        <input type="text" name="hp" value="{{ old('hp', $siswa->hp) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $siswa->email) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">No Telepon Rumah</label>
                        <input type="text" name="telepon" value="{{ old('telepon', $siswa->telepon) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Lintang (Latitude)</label>
                        <input type="text" name="lintang" value="{{ old('lintang', $siswa->lintang) }}" placeholder="Contoh: -6.12345" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-mono font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Bujur (Longitude)</label>
                        <input type="text" name="bujur" value="{{ old('bujur', $siswa->bujur) }}" placeholder="Contoh: 106.12345" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-mono font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Tinggal</label>
                        <input type="text" name="jenis_tinggal" value="{{ old('jenis_tinggal', $siswa->jenis_tinggal) }}" placeholder="Bersama Orang Tua / Wali / Kost" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold">
                    </div>
                </div>
            </section>

             {{-- 4. DATA ORANG TUA (AYAH & IBU) --}}
             <section class="space-y-8 bg-slate-50/50 -mx-10 px-10 py-12 border-y border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center font-black">04</div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Data Orang Tua Kandung</h3>
                    <div class="h-px bg-slate-200 flex-1 ml-4"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Form Ayah -->
                    <div class="space-y-6">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-200 pb-2">Bagian Ayah</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[9px] font-bold text-slate-500 uppercase">Nama Lengkap Ayah</label>
                                <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $siswa->nama_ayah) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold bg-white">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-bold text-slate-500 uppercase">NIK Ayah</label>
                                <input type="text" name="nik_ayah" value="{{ old('nik_ayah', $siswa->nik_ayah) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold bg-white">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-bold text-slate-500 uppercase">Tahun Lahir</label>
                                <input type="number" name="tahun_lahir_ayah" value="{{ old('tahun_lahir_ayah', $siswa->tahun_lahir_ayah) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold bg-white">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-bold text-slate-500 uppercase">Pendidikan Terakhir</label>
                                <input type="text" name="jenjang_pendidikan_ayah" value="{{ old('jenjang_pendidikan_ayah', $siswa->jenjang_pendidikan_ayah) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold bg-white">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-bold text-slate-500 uppercase">Pekerjaan</label>
                                <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $siswa->pekerjaan_ayah) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold bg-white">
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[9px] font-bold text-slate-500 uppercase">Penghasilan Bulanan</label>
                                <input type="text" name="penghasilan_ayah" value="{{ old('penghasilan_ayah', $siswa->penghasilan_ayah) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold bg-white">
                            </div>
                        </div>
                    </div>

                    <!-- Form Ibu -->
                    <div class="space-y-6">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-200 pb-2">Bagian Ibu</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[9px] font-bold text-slate-500 uppercase">Nama Lengkap Ibu</label>
                                <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $siswa->nama_ibu) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold bg-white">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-bold text-slate-500 uppercase">NIK Ibu</label>
                                <input type="text" name="nik_ibu" value="{{ old('nik_ibu', $siswa->nik_ibu) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold bg-white">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-bold text-slate-500 uppercase">Tahun Lahir</label>
                                <input type="number" name="tahun_lahir_ibu" value="{{ old('tahun_lahir_ibu', $siswa->tahun_lahir_ibu) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold bg-white">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-bold text-slate-500 uppercase">Pendidikan Terakhir</label>
                                <input type="text" name="jenjang_pendidikan_ibu" value="{{ old('jenjang_pendidikan_ibu', $siswa->jenjang_pendidikan_ibu) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold bg-white">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-bold text-slate-500 uppercase">Pekerjaan</label>
                                <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $siswa->pekerjaan_ibu) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold bg-white">
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[9px] font-bold text-slate-500 uppercase">Penghasilan Bulanan</label>
                                <input type="text" name="penghasilan_ibu" value="{{ old('penghasilan_ibu', $siswa->penghasilan_ibu) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold bg-white">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

             {{-- 5. DATA WALI --}}
             <section class="space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center font-black">05</div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Data Wali (Opsional)</h3>
                    <div class="h-px bg-slate-100 flex-1 ml-4"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Wali</label>
                        <input type="text" name="nama_wali" value="{{ old('nama_wali', $siswa->nama_wali) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">NIK Wali</label>
                        <input type="text" name="nik_wali" value="{{ old('nik_wali', $siswa->nik_wali) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold font-mono">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tahun Lahir</label>
                        <input type="number" name="tahun_lahir_wali" value="{{ old('tahun_lahir_wali', $siswa->tahun_lahir_wali) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pendidikan</label>
                        <input type="text" name="jenjang_pendidikan_wali" value="{{ old('jenjang_pendidikan_wali', $siswa->jenjang_pendidikan_wali) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pekerjaan</label>
                        <input type="text" name="pekerjaan_wali" value="{{ old('pekerjaan_wali', $siswa->pekerjaan_wali) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Penghasilan</label>
                        <input type="text" name="penghasilan_wali" value="{{ old('penghasilan_wali', $siswa->penghasilan_wali) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold">
                    </div>
                </div>
            </section>

            {{-- 6. PERIODIK, KESEHATAN & FISIK --}}
            <section class="space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center font-black">06</div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Kesehatan & Fisik</h3>
                    <div class="h-px bg-slate-100 flex-1 ml-4"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Golongan Darah</label>
                        <input type="text" name="golongan_darah" value="{{ old('golongan_darah', $siswa->golongan_darah) }}" placeholder="A/B/AB/O" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold text-center">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">TB (cm)</label>
                        <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan', $siswa->tinggi_badan) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold text-center">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">BB (kg)</label>
                        <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan', $siswa->berat_badan) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold text-center">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Lingkar Kepala (cm)</label>
                        <input type="number" step="0.1" name="lingkar_kepala" value="{{ old('lingkar_kepala', $siswa->lingkar_kepala) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold text-center">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Riwayat Penyakit</label>
                        <textarea name="riwayat_penyakit" rows="2" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold text-sm">{{ old('riwayat_penyakit', $siswa->riwayat_penyakit) }}</textarea>
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kebutuhan Khusus</label>
                        <textarea name="kebutuhan_khusus" rows="2" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold text-sm">{{ old('kebutuhan_khusus', $siswa->kebutuhan_khusus) }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jarak Sekolah (km)</label>
                        <input type="number" step="0.1" name="jarak_rumah_ke_sekolah_km" value="{{ old('jarak_rumah_ke_sekolah_km', $siswa->jarak_rumah_ke_sekolah_km) }}" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 font-bold">
                    </div>
                </div>
            </section>

            {{-- 7. BANTUAN, ADMINISTRASI & PERBANKAN --}}
            <section class="space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-800 text-white rounded-xl flex items-center justify-center font-black">07</div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Bantuan, Administrasi & Bank</h3>
                    <div class="h-px bg-slate-100 flex-1 ml-4"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Bantuan -->
                    <div class="bg-slate-50 p-6 rounded-[2rem] space-y-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Bantuan</p>
                        <div class="space-y-4">
                             <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-600">Penerima KIP?</label>
                                <select name="penerima_kip" class="bg-transparent border-none font-black text-sky-600 focus:ring-0 text-xs text-right">
                                    <option value="Ya" {{ old('penerima_kip', $siswa->penerima_kip) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="Tidak" {{ old('penerima_kip', $siswa->penerima_kip) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                             </div>
                             <input type="text" name="nomor_kip" value="{{ old('nomor_kip', $siswa->nomor_kip) }}" placeholder="Nomor KIP" class="w-full bg-white px-4 py-2 rounded-lg text-xs font-mono font-bold border-slate-200">
                             <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-600">Layak PIP?</label>
                                <select name="layak_pip" class="bg-transparent border-none font-black text-emerald-600 focus:ring-0 text-xs text-right">
                                    <option value="Ya" {{ old('layak_pip', $siswa->layak_pip) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="Tidak" {{ old('layak_pip', $siswa->layak_pip) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                             </div>
                        </div>
                    </div>

                    <!-- Administrasi -->
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase">Sekolah Asal</label>
                            <input type="text" name="sekolah_asal" value="{{ old('sekolah_asal', $siswa->sekolah_asal) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase">No. Seri Ijazah</label>
                            <input type="text" name="no_seri_ijazah" value="{{ old('no_seri_ijazah', $siswa->no_seri_ijazah) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold">
                        </div>
                        <div class="space-y-2">
                             <label class="text-[9px] font-black text-slate-400 uppercase">Nama Bank</label>
                             <input type="text" name="bank" value="{{ old('bank', $siswa->bank) }}" placeholder="Contoh: BRI / BNI" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold">
                        </div>
                        <div class="space-y-2">
                             <label class="text-[9px] font-black text-slate-400 uppercase">Nomor Rekening</label>
                             <input type="text" name="nomor_rekening_bank" value="{{ old('nomor_rekening_bank', $siswa->nomor_rekening_bank) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold font-mono">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                             <label class="text-[9px] font-black text-slate-400 uppercase">Status Siswa Saat Ini</label>
                             <select name="status" class="w-full px-4 py-3 rounded-xl border-2 border-slate-800 font-black text-slate-800 appearance-none bg-white">
                                @foreach(['Aktif', 'Lulus', 'Keluar/Mutasi'] as $st)
                                    <option value="{{ $st }}" {{ old('status', $siswa->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ACTION BUTTONS -->
            <div class="pt-10 border-t border-slate-100 flex flex-col md:flex-row gap-4">
                <a href="{{ route('siswas.show', $siswa) }}" class="flex-1 py-4 text-center text-sm font-black text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-2xl transition-all uppercase tracking-widest">
                    Batalkan Perubahan
                </a>
                <button type="submit" class="flex-[2] py-4 bg-slate-800 hover:bg-black text-white text-sm font-black rounded-2xl shadow-2xl shadow-slate-200 transition-all hover:-translate-y-1 active:translate-y-0 uppercase tracking-[0.2em]">
                    Simpan Seluruh Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
