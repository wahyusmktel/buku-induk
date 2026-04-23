<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Aktif - {{ $siswa->nama }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin-top: {{ $settings['margin_top'] ?? '2.5' }}cm;
            margin-bottom: {{ $settings['margin_bottom'] ?? '2.5' }}cm;
            margin-left: 0;
            margin-right: 0;
        }
        @page :first {
            margin-top: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .kop-surat-full {
            width: 100%;
            display: block;
            margin: 0;
            padding: 0;
        }

        .content-wrapper {
            margin-top: 5px;
            margin-right: {{ $settings['margin_right'] ?? '2.5' }}cm;
            margin-bottom: 0;
            margin-left: {{ $settings['margin_left'] ?? '2.5' }}cm;
        }

        /* KOP SURAT HTML (fallback jika tidak ada gambar kop) */
        .kop {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-bottom: 10px;
            border-bottom: 3px solid #000;
            margin-bottom: 6px;
        }

        .kop-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .kop-logo-placeholder {
            width: 70px;
            height: 70px;
            border: 1px solid #999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9pt;
            color: #666;
            flex-shrink: 0;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text .instansi {
            font-size: 10pt;
            font-weight: normal;
        }

        .kop-text .nama-sekolah {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .kop-text .alamat {
            font-size: 9.5pt;
            margin-top: 2px;
        }

        .kop-garis-bawah {
            border-bottom: 1px solid #000;
            margin-bottom: 24px;
        }

        /* JUDUL */
        .judul {
            text-align: center;
            margin-bottom: 6px;
            margin-top: 20px;
        }

        .judul h2 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: underline;
        }

        .nomor-surat {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 20px;
        }

        /* ISI SURAT */
        .paragraf {
            text-align: justify;
            line-height: 1.8;
            margin-bottom: 12px;
            text-indent: 30px;
        }

        /* TABEL DATA SISWA */
        .tabel-data {
            margin: 16px 0 16px 40px;
            width: calc(100% - 40px);
            border-collapse: collapse;
        }

        .tabel-data td {
            padding: 3px 6px;
            vertical-align: top;
            font-size: 12pt;
            line-height: 1.6;
        }

        .tabel-data td:first-child {
            width: 180px;
            font-weight: normal;
        }

        .tabel-data td:nth-child(2) {
            width: 12px;
            text-align: center;
        }

        .tabel-data td:last-child {
            font-weight: bold;
        }

        /* PENUTUP */
        .penutup {
            text-align: justify;
            line-height: 1.8;
            margin-bottom: 30px;
            text-indent: 30px;
        }

        /* TTD SECTION */
        .signature-wrapper {
            page-break-inside: avoid;
            break-inside: avoid;
            margin-top: 20px;
            text-align: right;
            width: 100%;
            clear: both;
        }

        .signature-box {
            display: inline-block;
            width: 280px;
            text-align: left;
            position: relative;
        }

        .signature-box p {
            margin: 0;
            padding: 0;
            line-height: 1.6;
            font-size: 12pt;
        }

        .signature-space {
            position: relative;
            height: 95px;
            width: 100%;
        }

        .stempel-img {
            height: 95px;
            width: auto;
            position: absolute;
            left: 5px;
            top: 0;
            z-index: 1;
        }

        .ttd-img {
            height: 78px;
            width: auto;
            position: absolute;
            left: 45px;
            top: 8px;
            z-index: 2;
        }

        /* PRINT STYLES */
        @media print {
            body {
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(79,70,229,0.3);
        }
    </style>
</head>
<body>

    @if(!isset($is_pdf) || !$is_pdf)
    <button class="print-btn no-print" onclick="window.print()">Cetak Surat</button>
    @endif

    {{-- KOP SURAT: gambar jika tersedia, fallback ke kop HTML --}}
    @if(!empty($settings['sekolah_kop_pdf']))
        <img src="{{ $settings['sekolah_kop_pdf'] }}" class="kop-surat-full">
    @elseif(!empty($settings['sekolah_kop']))
        <img src="{{ storage_path('app/public/' . $settings['sekolah_kop']) }}" class="kop-surat-full">
    @else
        <div style="margin: 20mm 25mm 0 30mm;">
            <div class="kop">
                @if(!empty($settings['sekolah_logo']) && file_exists(storage_path('app/public/' . $settings['sekolah_logo'])))
                    <img src="{{ storage_path('app/public/' . $settings['sekolah_logo']) }}"
                         alt="Logo" class="kop-logo">
                @else
                    <div class="kop-logo-placeholder">LOGO</div>
                @endif

                <div class="kop-text">
                    @php
                        $jenjang = $settings['jenjang_pendidikan'] ?? 'SD';
                        $namaSekolah = $settings['sekolah_nama'] ?? 'Nama Sekolah';
                    @endphp
                    <div class="instansi">PEMERINTAH KOTA/KABUPATEN</div>
                    <div class="instansi">DINAS PENDIDIKAN</div>
                    <div class="nama-sekolah">{{ $jenjang }} {{ $namaSekolah }}</div>
                    <div class="alamat">
                        {{ $settings['sekolah_alamat'] ?? '' }}
                        @if(!empty($settings['buku_induk_kota'])) &mdash; {{ $settings['buku_induk_kota'] }} @endif
                    </div>
                </div>
            </div>
            <div class="kop-garis-bawah"></div>
        </div>
    @endif

    <div class="content-wrapper">

        @php
            $jenjang = $settings['jenjang_pendidikan'] ?? 'SD';
            $namaSekolah = $settings['sekolah_nama'] ?? 'Nama Sekolah';
        @endphp

        {{-- JUDUL --}}
        <div class="judul">
            <h2>Surat Keterangan Aktif Sekolah</h2>
        </div>
        <div class="nomor-surat">
            Nomor: ......... / ......... / {{ now()->year }}
        </div>

        {{-- ISI SURAT --}}
        @php
            $kepsekNama = $settings['kepsek_nama'] ?? '...';
            $kepsekNip  = $settings['kepsek_nip']  ?? '';
            $rombel     = $siswa->rombel;
            $tahunPel   = $siswa->tahunPelajaran;
        @endphp

        <p class="paragraf">
            Yang bertanda tangan di bawah ini, Kepala {{ $jenjang }} {{ $namaSekolah }}, dengan ini menerangkan
            bahwa siswa yang namanya tersebut di bawah ini:
        </p>

        <table class="tabel-data">
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td>{{ $siswa->nama }}</td>
            </tr>
            <tr>
                <td>NISN</td>
                <td>:</td>
                <td>{{ $siswa->nisn ?? '-' }}</td>
            </tr>
            <tr>
                <td>NIS / NIPD</td>
                <td>:</td>
                <td>{{ $siswa->nipd ?? $siswa->nis ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>
                    {{ $siswa->tempat_lahir ?? '-' }},
                    {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>:</td>
                <td>{{ $siswa->agama ?? '-' }}</td>
            </tr>
            <tr>
                <td>Kelas / Rombel</td>
                <td>:</td>
                <td>{{ $rombel ? $rombel->nama : ($siswa->rombel_saat_ini ?? '-') }}</td>
            </tr>
            <tr>
                <td>Tahun Pelajaran</td>
                <td>:</td>
                <td>
                    {{ $tahunPel ? $tahunPel->tahun : '-' }}
                    @if($tahunPel) Semester {{ $tahunPel->semester }} @endif
                </td>
            </tr>
        </table>

        <p class="paragraf">
            benar-benar merupakan siswa aktif di {{ $jenjang }} {{ $namaSekolah }}
            pada Tahun Pelajaran {{ $tahunPel ? $tahunPel->tahun : '...' }}
            Semester {{ $tahunPel ? $tahunPel->semester : '...' }}.
        </p>

        <p class="penutup">
            Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana
            mestinya.
        </p>

        {{-- TANDA TANGAN --}}
        <div class="signature-wrapper">
            <div class="signature-box">
                <p>
                    {{ $settings['buku_induk_kota'] ?? '..........' }},
                    {{ !empty($settings['buku_induk_tanggal']) ? $settings['buku_induk_tanggal'] : \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </p>
                <p>Kepala {{ $jenjang }}</p>
                <p>{{ $namaSekolah }}</p>

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

                <p><strong><u>{{ $kepsekNama }}</u></strong></p>
                @if($kepsekNip)
                <p>NIP. {{ $kepsekNip }}</p>
                @endif
            </div>
        </div>

    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("Times New Roman", "italic");
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
