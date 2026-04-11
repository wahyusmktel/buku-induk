<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Induk — {{ $siswa->nama }}</title>
    <style>
        @page { 
            margin: {{ $settings['margin_top'] ?? '2.5' }}cm {{ $settings['margin_right'] ?? '2.5' }}cm {{ $settings['margin_bottom'] ?? '2.5' }}cm {{ $settings['margin_left'] ?? '2.5' }}cm; 
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 10pt; 
            color: #1f2937; 
            background: white; 
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        @media print {
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
        h1 { font-size: 14pt; font-weight: bold; text-align: center; text-transform: uppercase; letter-spacing: 2px; color: #111827; }
        h2 { font-size: 12pt; font-weight: normal; text-align: center; text-transform: uppercase; color: #4b5563; margin-top: 4px; }
        .school-header { text-align: center; border-bottom: 2px solid #111827; padding-bottom: 12px; margin-bottom: 24px; }
        .school-header p { font-size: 10pt; margin-top: 6px; font-weight: bold; color: #374151; }
        
        .section-title { 
            font-weight: bold; 
            font-size: 11pt; 
            text-transform: uppercase; 
            margin: 20px 0 12px; 
            padding-bottom: 4px;
            border-bottom: 1px solid #d1d5db;
            color: #111827;
        }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 10pt; }
        table.data-table td { padding: 4px 6px; vertical-align: top; word-wrap: break-word; color: #374151; }
        table.data-table tr td:first-child { width: 5.8cm; color: #4b5563; }
        table.data-table tr td:nth-child(2) { width: 15px; text-align: center; color: #6b7280; }
        table.data-table tr td:nth-child(3) { font-weight: bold; color: #111827; }
        
        .sub-header td { padding-top: 12px !important; font-weight: bold; color: #111827 !important; }
        
        table.bordered { width: 100%; border-collapse: collapse; font-size: 8.5pt; table-layout: fixed; margin-bottom: 15px; }
        table.bordered th, table.bordered td { border: 1px solid #9ca3af; padding: 5px; text-align: center; word-wrap: break-word; color: #1f2937; }
        table.bordered th { background-color: #f3f4f6; font-weight: bold; text-transform: uppercase; font-size: 7.5pt; color: #374151; }
        table.bordered td.left { text-align: left; padding-left: 6px; }
        
        .signature-area { display: flex; justify-content: flex-end; margin-top: 40px; float: right; width: 250px; text-align: center; }
        .signature-box { text-align: center; }
        .signature-line { border-bottom: 1px solid #111827; margin: 60px auto 5px; width: 200px; }
        
        .print-btn { position: fixed; top: 20px; right: 20px; padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 8px; font-family: 'Helvetica', sans-serif; font-size: 12px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 12px rgba(79,70,229,0.3); }

        /* Photo Styles */
        .photo-container { position: absolute; top: 40px; right: 0; display: flex; flex-direction: column; gap: 15px; }
        .photo-box { width: 3cm; height: 4cm; border: 1px dashed #9ca3af; display: flex; align-items: center; justify-center; overflow: hidden; background: #f9fafb; padding: 2px; }
        .photo-box img { width: 100%; height: 100%; object-fit: cover; border: 1px solid #e5e7eb; }
        .photo-label { font-size: 7pt; text-align: center; font-weight: bold; margin-top: 4px; text-transform: uppercase; color: #6b7280; letter-spacing: 1px; }
    </style>
</head>
<body>

    @if(!isset($is_pdf) || !$is_pdf)
    <button class="print-btn no-print" onclick="window.print()">🖨️ Cetak Buku Induk</button>
    @endif

    {{-- PAGE 1: Identity --}}
    <div class="school-header">
        <h1>Buku Induk Siswa</h1>
        <h2>Sekolah Dasar / Menengah</h2>
        <p>Tahun Pelajaran {{ $siswa->rombel_saat_ini ?? '' }}</p>
    </div>

    <table style="width: 100%; border: none; margin-bottom: 10px;">
        <tr>
            <td style="vertical-align: top; padding-right: 20px;">
                <p class="section-title" style="margin-top: 0;">I. Identitas Murid</p>
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
            <td style="width: 3.2cm; vertical-align: top; padding-top: 10px;">
                <div class="photo-box" style="margin-bottom: 5px;">
                    @if($bukuInduk->foto_1)
                        <img src="{{ public_path('storage/' . $bukuInduk->foto_1) }}">
                    @else
                        <div style="text-align:center; color:#9ca3af; font-size:8pt; width: 100%; margin-top: 1.5cm;">Pas Foto<br/>3 x 4</div>
                    @endif
                </div>
                <p class="photo-label" style="margin-bottom: 15px;">FOTO 1</p>

                <div class="photo-box" style="margin-bottom: 5px;">
                    @if($bukuInduk->foto_2)
                        <img src="{{ public_path('storage/' . $bukuInduk->foto_2) }}">
                    @else
                        <div style="text-align:center; color:#9ca3af; font-size:8pt; width: 100%; margin-top: 1.5cm;">Pas Foto<br/>3 x 4</div>
                    @endif
                </div>
                <p class="photo-label">FOTO 2</p>
            </td>
        </tr>
    </table>

    <p class="section-title">II. Data Periodik</p>
    <table class="data-table">
        @php $periodik = $siswa->dataPeriodik; @endphp
            <tr><td>1. Jumlah Saudara Kandung</td><td>:</td><td>{{ $periodik?->jml_saudara_kandung ?? '0' }}</td></tr>
            <tr><td>2. Jumlah Saudara Tiri</td><td>:</td><td>{{ $periodik?->jml_saudara_tiri ?? '0' }}</td></tr>
            <tr><td>3. Jumlah Saudara Angkat</td><td>:</td><td>{{ $periodik?->jml_saudara_angkat ?? '0' }}</td></tr>
            <tr><td>4. Bahasa Sehari-hari di Rumah</td><td>:</td><td>{{ $periodik?->bahasa_sehari_hari ?? '—' }}</td></tr>
            <tr><td>5. Alamat Tempat Tinggal</td><td>:</td><td>{{ $periodik?->alamat_tinggal ?? '—' }}</td></tr>
            <tr><td>6. Bertempat Tinggal Pada</td><td>:</td><td>{{ $periodik?->bertempat_tinggal_pada ?? '—' }}</td></tr>
            <tr><td>7. Jarak Ke Sekolah</td><td>:</td><td>{{ $periodik?->jarak_tempat_tinggal_ke_sekolah ?? '—' }}</td></tr>
        </table>

        <p class="section-title">III. Keadaan Jasmani</p>
        <table class="data-table">
            @php $jasmani = $siswa->keadaanJasmani; @endphp
            <tr><td>1. Berat Badan</td><td>:</td><td>{{ $jasmani?->berat_badan ?? '—' }} kg</td></tr>
            <tr><td>2. Tinggi Badan</td><td>:</td><td>{{ $jasmani?->tinggi_badan ?? '—' }} cm</td></tr>
            <tr><td>3. Golongan Darah</td><td>:</td><td>{{ $jasmani?->golongan_darah ?? '—' }}</td></tr>
            <tr><td>4. Riwayat Penyakit</td><td>:</td><td>{{ $jasmani?->nama_riwayat_penyakit ?? 'Tidak ada' }}</td></tr>
            <tr><td>5. Kelainan Jasmani</td><td>:</td><td>{{ $jasmani?->kelainan_jasmani ?? 'Tidak ada' }}</td></tr>
        </table>
    </div>

    <div class="page-break">
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
        
        <p class="section-title">V. Data Orang Tua / Wali</p>
        <table class="data-table">
            @php 
                $ayah = $siswa->dataOrangTua->where('jenis', 'Ayah')->first();
                $ibu = $siswa->dataOrangTua->where('jenis', 'Ibu')->first();
                $wali = $siswa->dataOrangTua->where('jenis', 'Wali')->first();
            @endphp
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

    {{-- PAGE 3: Academic Records & Beasiswa --}}
    <div class="page-break">
        <p class="section-title">VI. Prestasi Belajar</p>
        <table class="bordered">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 25px;">Kls</th>
                    <th rowspan="2" style="width: 25px;">Smt</th>
                    <th rowspan="2" style="width: 80px;">Thn Pelajaran</th>
                    <th colspan="{{ $mataPelajarans->count() }}">Nilai Mata Pelajaran</th>
                    <th rowspan="2" style="width: 30px;">Jml</th>
                    <th rowspan="2" style="width: 30px;">Rata²</th>
                    <th rowspan="2" style="width: 30px;">Rank</th>
                    <th colspan="3">Kehadiran</th>
                    <th rowspan="2" style="width: 40px;">Naik?</th>
                </tr>
                <tr>
                    @foreach($mataPelajarans as $mapel)
                    <th style="font-size: 6.5pt; font-weight: normal; overflow: hidden; text-overflow: ellipsis; word-wrap: break-word;" title="{{ $mapel->nama }}">
                        {{ substr($mapel->nama, 0, 8) }}{{ strlen($mapel->nama) > 8 ? '.' : '' }}
                    </th>
                    @endforeach
                    <th style="width: 15px;">S</th><th style="width: 15px;">I</th><th style="width: 15px;">A</th>
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
                    <td class="left" style="font-size:7pt; white-space: nowrap;">{{ $p?->tahun_pelajaran ?? '' }}</td>
                    
                    @foreach($mataPelajarans as $mapel)
                    @php
                        $nilaiVal = $p ? $p->nilais->where('mata_pelajaran_id', $mapel->id)->first()?->nilai : null;
                    @endphp
                    <td style="font-size: 8.5pt;">{{ $nilaiVal ? number_format($nilaiVal, 0) : '' }}</td>
                    @endforeach
                    
                    <td><strong>{{ $p?->jumlah_nilai ? number_format($p->jumlah_nilai, 0) : '' }}</strong></td>
                    <td style="font-size: 8pt;">{{ $p?->rata_rata ?? '' }}</td>
                    <td>{{ $p?->peringkat ?? '' }}</td>
                    <td>{{ $p?->hadir_sakit ?? '' }}</td>
                    <td>{{ $p?->hadir_izin ?? '' }}</td>
                    <td>{{ $p?->hadir_alpha ?? '' }}</td>
                    <td style="font-size:7.5pt;">{{ ($p && $semester == 2) ? ($p->keterangan_kenaikan ?? '') : '' }}</td>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>

        <br/>
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

        <div class="signature-area">
            <div class="signature-box">
                <p>Mengetahui,</p>
                <p>Kepala Sekolah</p>
                <div class="signature-line"></div>
                <p>NIP. _________________________</p>
            </div>
        </div>
    </div>
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("Helvetica", "italic");
            $size = 7.5;
            $color = [0.4, 0.4, 0.4]; // grayish
            
            $w = $pdf->get_width();
            $h = $pdf->get_height();
            $y = $h - 40; // 40px from bottom edge

            // Teks Halaman - Kanan
            $text_right = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            // Approximate width to align right (dompdf handles text alignment loosely, so we offset)
            $width_right = $fontMetrics->get_text_width("Halaman 10 dari 10", $font, $size); 
            $pdf->page_text($w - $width_right - 45, $y, $text_right, $font, $size, $color);

            // Teks Info Cetak - Kiri
            $sekolah = {!! json_encode($settings['sekolah_nama'] ?? '') !!};
            $text_left = "Dicetak melalui Aplikasi Buku Induk" . ($sekolah ? " " . $sekolah : "");
            $pdf->page_text(45, $y, $text_left, $font, $size, $color);
            
            // Garis tipis pembatas footer
            $pdf->line(45, $y - 10, $w - 45, $y - 10, [0.8, 0.8, 0.8], 0.5);
        }
    </script>
</body>
</html>
