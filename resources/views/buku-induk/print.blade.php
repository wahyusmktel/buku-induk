<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Induk — {{ $siswa->nama }}</title>
    <style>
        @page { 
            margin: 0; 
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 9.5pt; 
            color: #1f2937; 
            background: white; 
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .content-wrapper {
            margin: 5px {{ $settings['margin_right'] ?? '2' }}cm {{ $settings['margin_bottom'] ?? '2' }}cm {{ $settings['margin_left'] ?? '2' }}cm;
            position: relative;
        }
        .kop-surat-full {
            width: 100%;
            display: block;
            margin: 0;
            padding: 0;
        }
        @media print {
            .no-print { display: none !important; }
        }
        h1 { font-size: 13pt; font-weight: bold; text-align: center; text-transform: uppercase; letter-spacing: 2px; color: #111827; margin: 0; }
        h2 { font-size: 11pt; font-weight: normal; text-align: center; text-transform: uppercase; color: #4b5563; margin: 2px 0; }
        .school-header { text-align: center; border-bottom: 2px solid #111827; padding-bottom: 6px; margin-bottom: 10px; }
        .school-header p { font-size: 9.5pt; margin: 2px 0 0; font-weight: bold; color: #374151; }
        
        /* ====================================================
           SECTION BLOCKS — tidak boleh terpotong halaman
           ==================================================== */
        .section-block {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .section-title { 
            font-weight: bold; 
            font-size: 10pt; 
            text-transform: uppercase; 
            margin: 8px 0 6px; 
            padding: 3px 0 3px;
            border-bottom: 1px solid #d1d5db;
            color: #111827;
        }

        /* Page break BEFORE section (force ke halaman baru) */
        .page-break-before {
            page-break-before: always;
            break-before: page;
        }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; font-size: 9.5pt; }
        table.data-table td { padding: 2.5px 5px; vertical-align: top; word-wrap: break-word; color: #374151; }
        table.data-table tr td:first-child { width: 5.5cm; color: #4b5563; }
        table.data-table tr td:nth-child(2) { width: 12px; text-align: center; color: #6b7280; }
        table.data-table tr td:nth-child(3) { font-weight: bold; color: #111827; }
        
        .sub-header td { padding-top: 8px !important; font-weight: bold; color: #111827 !important; background-color: #f9fafb; font-size: 9pt; }
        
        table.bordered { width: 100%; border-collapse: collapse; font-size: 8pt; table-layout: fixed; margin-bottom: 10px; }
        table.bordered th, table.bordered td { border: 1px solid #9ca3af; padding: 4px 3px; text-align: center; word-wrap: break-word; color: #1f2937; }
        table.bordered th { background-color: #f3f4f6; font-weight: bold; text-transform: uppercase; font-size: 7pt; color: #374151; }
        table.bordered td.left { text-align: left; padding-left: 5px; }

        /* ====================================================
           TANDA TANGAN — harus selalu utuh, tidak terpotong
           ==================================================== */
        .signature-wrapper { 
            page-break-inside: avoid;
            break-inside: avoid;
            margin-top: 20px; 
            text-align: right; 
            width: 100%; 
            clear: both; 
        }
        .signature-box { display: inline-block; width: 280px; text-align: left; position: relative; }
        .signature-box p { margin: 0; padding: 0; line-height: 1.4; }
        .signature-space { position: relative; height: 95px; width: 100%; }
        .stempel-img { height: 95px; width: auto; position: absolute; left: 5px; top: 0; z-index: 1; }
        .ttd-img { height: 78px; width: auto; position: absolute; left: 45px; top: 8px; z-index: 2; }
        
        .print-btn { position: fixed; top: 20px; right: 20px; padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 8px; font-family: 'Helvetica', sans-serif; font-size: 12px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 12px rgba(79,70,229,0.3); }

        /* Photo Styles */
        .photo-box { width: 3cm; height: 4cm; border: 1px dashed #9ca3af; overflow: hidden; background: #f9fafb; padding: 2px; }
        .photo-box img { width: 100%; height: 100%; object-fit: cover; border: 1px solid #e5e7eb; }
        .photo-label { font-size: 7pt; text-align: center; font-weight: bold; margin-top: 3px; text-transform: uppercase; color: #6b7280; letter-spacing: 1px; }

        /* Layout kolom dua untuk baris pertama */
        .two-col-table { width: 100%; border-collapse: collapse; }
        .two-col-table td { vertical-align: top; }
    </style>
</head>
<body>

    @if(!isset($is_pdf) || !$is_pdf)
    <button class="print-btn no-print" onclick="window.print()">🖨️ Cetak Buku Induk</button>
    @endif

    @if(!empty($settings['sekolah_kop_pdf']))
        <img src="{{ $settings['sekolah_kop_pdf'] }}" class="kop-surat-full">
    @elseif(!empty($settings['sekolah_kop']))
        <img src="{{ storage_path('app/public/' . $settings['sekolah_kop']) }}" class="kop-surat-full">
    @endif

    <div class="content-wrapper">

        {{-- ===== HEADER JUDUL ===== --}}
        <div class="school-header">
            <h1>Buku Induk Siswa</h1>
            <h2>{{ $settings['sekolah_nama'] ?? 'Sekolah Dasar / Menengah' }}</h2>
            <p>Tahun Pelajaran {{ $siswa->tahunPelajaran ? $siswa->tahunPelajaran->tahun . ' / Semester ' . $siswa->tahunPelajaran->semester : ($siswa->rombel_saat_ini ?? '—') }}</p>
        </div>

        {{-- ===== I. IDENTITAS MURID (dengan foto, tidak boleh terpotong) ===== --}}
        <div class="section-block">
            <p class="section-title">I. Identitas Murid</p>
            <table class="two-col-table">
                <tr>
                    <td style="padding-right: 15px;">
                        <table class="data-table" style="margin-bottom: 0;">
                            <tr><td>1. Nomor Induk Sekolah (NIS)</td><td>:</td><td>{{ $siswa->nipd ?? '—' }}</td></tr>
                            <tr><td>2. NISN</td><td>:</td><td>{{ $siswa->nisn ?? '—' }}</td></tr>
                            <tr><td>3. NIK</td><td>:</td><td>{{ $siswa->nik ?? '—' }}</td></tr>
                            <tr><td>4. Nama Lengkap</td><td>:</td><td style="text-transform: uppercase;">{{ $siswa->nama }}</td></tr>
                            <tr><td>5. Nama Panggilan</td><td>:</td><td>{{ $siswa->nama_panggilan ?? '—' }}</td></tr>
                            <tr><td>6. Jenis Kelamin</td><td>:</td><td>{{ $siswa->jk == 'L' ? 'Laki-laki' : ($siswa->jk == 'P' ? 'Perempuan' : '—') }}</td></tr>
                            <tr><td>7. Tempat, Tanggal Lahir</td><td>:</td><td>{{ $siswa->tempat_lahir ?? '—' }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') : '—' }}</td></tr>
                            <tr><td>8. Agama</td><td>:</td><td>{{ $siswa->agama ?? '—' }}</td></tr>
                            <tr><td>9. Kewarganegaraan</td><td>:</td><td>{{ $siswa->kewarganegaraan ?? 'WNI' }}</td></tr>
                            <tr><td>10. Nomor Telepon / HP</td><td>:</td><td>{{ $siswa->hp ?? $siswa->telepon ?? '—' }}</td></tr>
                        </table>
                    </td>
                    <td style="width: 3.2cm; padding-top: 5px;">
                        <div class="photo-box" style="margin-bottom: 4px;">
                            @if($bukuInduk->foto_1)
                                <img src="{{ storage_path('app/public/' . $bukuInduk->foto_1) }}">
                            @else
                                <div style="text-align:center; color:#9ca3af; font-size:7.5pt; margin-top: 1.2cm;">Pas Foto<br/>3 x 4</div>
                            @endif
                        </div>
                        <p class="photo-label" style="margin-bottom: 10px;">FOTO 1</p>
                        <div class="photo-box" style="margin-bottom: 4px;">
                            @if($bukuInduk->foto_2)
                                <img src="{{ storage_path('app/public/' . $bukuInduk->foto_2) }}">
                            @else
                                <div style="text-align:center; color:#9ca3af; font-size:7.5pt; margin-top: 1.2cm;">Pas Foto<br/>3 x 4</div>
                            @endif
                        </div>
                        <p class="photo-label">FOTO 2</p>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ===== II. DATA PERIODIK ===== --}}
        <div class="section-block">
            <p class="section-title">II. Data Periodik</p>
            @php $periodik = $siswa->dataPeriodik; @endphp
            <table class="data-table">
                <tr><td>1. Jumlah Saudara Kandung</td><td>:</td><td>{{ $periodik?->jml_saudara_kandung ?? '0' }}</td></tr>
                <tr><td>2. Jumlah Saudara Tiri</td><td>:</td><td>{{ $periodik?->jml_saudara_tiri ?? '0' }}</td></tr>
                <tr><td>3. Jumlah Saudara Angkat</td><td>:</td><td>{{ $periodik?->jml_saudara_angkat ?? '0' }}</td></tr>
                <tr><td>4. Bahasa Sehari-hari di Rumah</td><td>:</td><td>{{ $periodik?->bahasa_sehari_hari ?? '—' }}</td></tr>
                <tr><td>5. Alamat Tempat Tinggal</td><td>:</td><td>{{ $periodik?->alamat_tinggal ?? '—' }}</td></tr>
                <tr><td>6. Bertempat Tinggal Pada</td><td>:</td><td>{{ $periodik?->bertempat_tinggal_pada ?? '—' }}</td></tr>
                <tr><td>7. Jarak Ke Sekolah</td><td>:</td><td>{{ $periodik?->jarak_tempat_tinggal_ke_sekolah ?? '—' }}</td></tr>
            </table>
        </div>

        {{-- ===== III. KEADAAN JASMANI ===== --}}
        <div class="section-block">
            <p class="section-title">III. Keadaan Jasmani</p>
            @php $jasmani = $siswa->keadaanJasmani; @endphp
            <table class="data-table">
                <tr><td>1. Berat Badan</td><td>:</td><td>{{ $jasmani?->berat_badan ?? '—' }} kg</td></tr>
                <tr><td>2. Tinggi Badan</td><td>:</td><td>{{ $jasmani?->tinggi_badan ?? '—' }} cm</td></tr>
                <tr><td>3. Golongan Darah</td><td>:</td><td>{{ $jasmani?->golongan_darah ?? '—' }}</td></tr>
                <tr><td>4. Riwayat Penyakit</td><td>:</td><td>{{ $jasmani?->nama_riwayat_penyakit ?? 'Tidak ada' }}</td></tr>
                <tr><td>5. Kelainan Jasmani</td><td>:</td><td>{{ $jasmani?->kelainan_jasmani ?? 'Tidak ada' }}</td></tr>
            </table>
        </div>

        {{-- ===== IV. PENDIDIKAN SEBELUMNYA ===== --}}
        <div class="section-block">
            <p class="section-title">IV. Pendidikan Sebelumnya</p>
            <table class="data-table">
                <tr class="sub-header"><td colspan="3">A. Masuk Menjadi Siswa Baru</td></tr>
                <tr><td>1. Asal Siswa</td><td>:</td><td>{{ $bukuInduk->asal_masuk_sekolah ?? '—' }}</td></tr>
                <tr><td>2. Nama Sekolah Asal</td><td>:</td><td>{{ $bukuInduk->nama_tk_asal ?? '—' }}</td></tr>
                <tr><td>3. Tanggal Masuk Sekolah Ini</td><td>:</td><td>{{ $bukuInduk->tgl_masuk_sekolah ? \Carbon\Carbon::parse($bukuInduk->tgl_masuk_sekolah)->translatedFormat('d F Y') : '—' }}</td></tr>
                
                <tr class="sub-header"><td colspan="3">B. Pindahan Dari Sekolah Lain</td></tr>
                <tr><td>1. Nama Sekolah Asal</td><td>:</td><td>{{ $bukuInduk->pindah_dari ?? '—' }}</td></tr>
                <tr><td>2. Dari Kelas</td><td>:</td><td>{{ $bukuInduk->kelas_pindah_masuk ? 'Kelas ' . $bukuInduk->kelas_pindah_masuk : '—' }}</td></tr>
                <tr><td>3. Diterima Tanggal</td><td>:</td><td>{{ $bukuInduk->tgl_pindah_masuk ? \Carbon\Carbon::parse($bukuInduk->tgl_pindah_masuk)->translatedFormat('d F Y') : '—' }}</td></tr>
                <tr><td>4. Di Kelas</td><td>:</td><td>{{ $bukuInduk->kelas_pindah_masuk ? 'Kelas ' . $bukuInduk->kelas_pindah_masuk : '—' }}</td></tr>
            </table>
        </div>

        {{-- ===== V. DATA ORANG TUA / WALI ===== --}}
        @php 
            $ayah = $siswa->dataOrangTua->where('jenis', 'Ayah')->first();
            $ibu  = $siswa->dataOrangTua->where('jenis', 'Ibu')->first();
            $wali = $siswa->dataOrangTua->where('jenis', 'Wali')->first();
        @endphp

        {{-- Sub-section Ayah: tidak boleh terpotong --}}
        <div class="section-block">
            <p class="section-title">V. Data Orang Tua / Wali</p>
            <table class="data-table">
                <tr class="sub-header"><td colspan="3">A. Ayah Kandung</td></tr>
                <tr><td>Nama Lengkap</td><td>:</td><td>{{ $ayah?->nama ?? '—' }}</td></tr>
                <tr><td>Tempat, Tgl Lahir</td><td>:</td><td>{{ $ayah?->tempat_lahir ?? '—' }}, {{ $ayah?->tanggal_lahir ? \Carbon\Carbon::parse($ayah->tanggal_lahir)->translatedFormat('d F Y') : '—' }}</td></tr>
                <tr><td>Agama</td><td>:</td><td>{{ $ayah?->agama ?? '—' }}</td></tr>
                <tr><td>Kewarganegaraan</td><td>:</td><td>{{ $ayah?->kewarganegaraan ?? '—' }}</td></tr>
                <tr><td>Pendidikan Terakhir</td><td>:</td><td>{{ $ayah?->pendidikan_terakhir ?? '—' }}</td></tr>
                <tr><td>Pekerjaan</td><td>:</td><td>{{ $ayah?->pekerjaan ?? '—' }}</td></tr>

                <tr class="sub-header"><td colspan="3">B. Ibu Kandung</td></tr>
                <tr><td>Nama Lengkap</td><td>:</td><td>{{ $ibu?->nama ?? '—' }}</td></tr>
                <tr><td>Tempat, Tgl Lahir</td><td>:</td><td>{{ $ibu?->tempat_lahir ?? '—' }}, {{ $ibu?->tanggal_lahir ? \Carbon\Carbon::parse($ibu->tanggal_lahir)->translatedFormat('d F Y') : '—' }}</td></tr>
                <tr><td>Agama</td><td>:</td><td>{{ $ibu?->agama ?? '—' }}</td></tr>
                <tr><td>Kewarganegaraan</td><td>:</td><td>{{ $ibu?->kewarganegaraan ?? '—' }}</td></tr>
                <tr><td>Pendidikan Terakhir</td><td>:</td><td>{{ $ibu?->pendidikan_terakhir ?? '—' }}</td></tr>
                <tr><td>Pekerjaan</td><td>:</td><td>{{ $ibu?->pekerjaan ?? '—' }}</td></tr>

                <tr class="sub-header"><td colspan="3">C. Wali Siswa (Bila Ada)</td></tr>
                <tr><td>Nama Lengkap</td><td>:</td><td>{{ $wali?->nama ?? '—' }}</td></tr>
                <tr><td>Hubungan Keluarga</td><td>:</td><td>{{ $wali?->hubungan_keluarga ?? '—' }}</td></tr>
                <tr><td>Pendidikan Terakhir</td><td>:</td><td>{{ $wali?->pendidikan_terakhir ?? '—' }}</td></tr>
                <tr><td>Pekerjaan</td><td>:</td><td>{{ $wali?->pekerjaan ?? '—' }}</td></tr>
            </table>
        </div>

        {{-- ===== VI. PRESTASI BELAJAR (halaman baru) ===== --}}
        <div class="page-break-before">
            <p class="section-title" style="margin-top: 0;">VI. Prestasi Belajar</p>
            <table class="bordered">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 22px;">Kls</th>
                        <th rowspan="2" style="width: 22px;">Smt</th>
                        <th rowspan="2" style="width: 75px;">Thn Pelajaran</th>
                        <th colspan="{{ $mataPelajarans->count() }}">Nilai Mata Pelajaran</th>
                        <th rowspan="2" style="width: 28px;">Jml</th>
                        <th rowspan="2" style="width: 28px;">Rata²</th>
                        <th rowspan="2" style="width: 28px;">Rank</th>
                        <th colspan="3">Kehadiran</th>
                        <th rowspan="2" style="width: 38px;">Naik?</th>
                    </tr>
                    <tr>
                        @foreach($mataPelajarans as $mapel)
                        <th style="font-size: 6pt; font-weight: normal; overflow: hidden; word-wrap: break-word;" title="{{ $mapel->nama }}">
                            {{ substr($mapel->nama, 0, 7) }}{{ strlen($mapel->nama) > 7 ? '.' : '' }}
                        </th>
                        @endforeach
                        <th style="width: 14px;">S</th><th style="width: 14px;">I</th><th style="width: 14px;">A</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(range(1, 6) as $kelas)
                    @foreach([1, 2] as $semester)
                    @php $p = $akademikGrid[$kelas][$semester] ?? null; @endphp
                    <tr>
                        @if($semester == 1)
                        <td rowspan="2" style="vertical-align:middle; font-weight:bold;">{{ $kelas }}</td>
                        @endif
                        <td>{{ $semester }}</td>
                        <td class="left" style="font-size:6.5pt; white-space: nowrap;">{{ $p?->tahun_pelajaran ?? '' }}</td>
                        
                        @foreach($mataPelajarans as $mapel)
                        @php
                            $nilaiVal = $p ? $p->nilais->where('mata_pelajaran_id', $mapel->id)->first()?->nilai : null;
                        @endphp
                        <td style="font-size: 8pt;">{{ $nilaiVal ? number_format($nilaiVal, 0) : '' }}</td>
                        @endforeach
                        
                        <td><strong>{{ $p?->jumlah_nilai ? number_format($p->jumlah_nilai, 0) : '' }}</strong></td>
                        <td style="font-size: 7.5pt;">{{ $p?->rata_rata ?? '' }}</td>
                        <td>{{ $p?->peringkat ?? '' }}</td>
                        <td>{{ $p?->hadir_sakit ?? '' }}</td>
                        <td>{{ $p?->hadir_izin ?? '' }}</td>
                        <td>{{ $p?->hadir_alpha ?? '' }}</td>
                        <td style="font-size:7pt;">{{ ($p && $semester == 2) ? ($p->keterangan_kenaikan ?? '') : '' }}</td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ===== VII. TAMAT BELAJAR / MENINGGALKAN SEKOLAH ===== --}}
        <div class="section-block">
            <p class="section-title">VII. Tamat Belajar / Meninggalkan Sekolah</p>
            <table class="data-table">
                @php $reg_tamat = $siswa->registrasi->where('jenis_registrasi', 'Tamat Belajar')->first(); @endphp
                <tr class="sub-header"><td colspan="3">A. Tamat Belajar</td></tr>
                <tr><td>1. Tahun Tamat</td><td>:</td><td>{{ $reg_tamat?->tahun_tamat ?? '—' }}</td></tr>
                <tr><td>2. Nomor Seri Ijazah</td><td>:</td><td>{{ $reg_tamat?->no_ijazah ?? '—' }}</td></tr>
                <tr><td>3. Melanjutkan Ke Sekolah</td><td>:</td><td>{{ $reg_tamat?->melanjutkan_ke ?? '—' }}</td></tr>

                @php $reg_pindah = $siswa->registrasi->where('jenis_registrasi', 'Pindah Sekolah')->first(); @endphp
                <tr class="sub-header"><td colspan="3">B. Pindah Sekolah</td></tr>
                <tr><td>1. Dari Sekolah</td><td>:</td><td>{{ $reg_pindah?->dari_sekolah ?? '—' }}</td></tr>
                <tr><td>2. Ke Sekolah</td><td>:</td><td>{{ $reg_pindah?->ke_sekolah ?? '—' }}</td></tr>

                @php $reg_keluar = $siswa->registrasi->where('jenis_registrasi', 'Keluar Sekolah')->first(); @endphp
                <tr class="sub-header"><td colspan="3">C. Keluar Sekolah</td></tr>
                <tr><td>1. Tanggal Keluar</td><td>:</td><td>{{ $reg_keluar?->tanggal_keluar ? \Carbon\Carbon::parse($reg_keluar->tanggal_keluar)->translatedFormat('d F Y') : '—' }}</td></tr>
                <tr><td>2. Alasan Keluar</td><td>:</td><td>{{ $reg_keluar?->alasan_keluar ?? '—' }}</td></tr>
            </table>
        </div>

        {{-- ===== TANDA TANGAN (tidak boleh terpotong sama sekali) ===== --}}
        <div class="signature-wrapper">
            <div class="signature-box">
                <p>{{ $settings['buku_induk_kota'] ?? '..........' }}, {{ !empty($settings['buku_induk_tanggal']) ? $settings['buku_induk_tanggal'] : \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p>Mengetahui,</p>
                <p>Kepala {{ $settings['sekolah_nama'] ?? 'Sekolah' }}</p>
                
                <div class="signature-space">
                    @if(!empty($settings['kepsek_ttd_pdf']))
                        <img src="{{ $settings['kepsek_ttd_pdf'] }}" class="ttd-img">
                    @elseif(!empty($settings['kepsek_ttd']))
                        <img src="{{ storage_path('app/public/' . $settings['kepsek_ttd']) }}" class="ttd-img">
                    @endif
                    @if(!empty($settings['sekolah_stempel_pdf']))
                        <img src="{{ $settings['sekolah_stempel_pdf'] }}" class="stempel-img">
                    @elseif(!empty($settings['sekolah_stempel']))
                        <img src="{{ storage_path('app/public/' . $settings['sekolah_stempel']) }}" class="stempel-img">
                    @endif
                </div>

                <p><strong><u>{{ $settings['kepsek_nama'] ?? '__________________________' }}</u></strong></p>
                <p>NIP. {{ $settings['kepsek_nip'] ?? '__________________________' }}</p>
            </div>
        </div>

    </div>
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("Helvetica", "italic");
            $size = 7.5;
            $color = [0.4, 0.4, 0.4];
            
            $w = $pdf->get_width();
            $h = $pdf->get_height();
            $y = $h - 40;

            $text_right = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $width_right = $fontMetrics->get_text_width("Halaman 10 dari 10", $font, $size); 
            $pdf->page_text($w - $width_right - 45, $y, $text_right, $font, $size, $color);

            $sekolah = {!! json_encode($settings['sekolah_nama'] ?? '') !!};
            $text_left = "Dicetak melalui Aplikasi Buku Induk" . ($sekolah ? " " . $sekolah : "");
            $pdf->page_text(45, $y, $text_left, $font, $size, $color);
            
            $pdf->line(45, $y - 10, $w - 45, $y - 10, [0.8, 0.8, 0.8], 0.5);
        }
    </script>
</body>
</html>
