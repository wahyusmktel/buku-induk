<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leger Nilai - {{ $rombel->nama }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #000;
            background: #fff;
        }

        .page {
            max-width: 297mm;
            min-height: 210mm;
            margin: 0 auto;
            padding-top: 10px;
            padding-right: {{ $settings['margin_right'] ?? '1.5' }}cm;
            padding-left: {{ $settings['margin_left'] ?? '1.5' }}cm;
            position: relative;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 14px;
        }

        .header .judul-utama {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header .sub-judul {
            font-size: 10pt;
            margin-top: 2px;
        }

        .header .info-rombel {
            font-size: 9pt;
            margin-top: 4px;
        }

        .garis {
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            padding: 2px 0;
            margin: 8px 0;
        }

        /* TABEL LEGER */
        .tabel-leger {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 7.5pt;
        }

        .tabel-leger th,
        .tabel-leger td {
            border: 1px solid #333;
            padding: 3px 2px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .tabel-leger thead th {
            background-color: #dbeafe;
            font-weight: bold;
            font-size: 7pt;
        }

        .tabel-leger tbody td.no {
            width: 20px;
        }

        .tabel-leger tbody td.nama-siswa {
            text-align: left;
            padding-left: 4px;
        }

        .tabel-leger tbody td.nisn {
            font-size: 6.5pt;
        }

        .tabel-leger tbody td.nilai {
        }

        .tabel-leger tbody td.jumlah,
        .tabel-leger tbody td.rata,
        .tabel-leger tbody td.peringkat {
            font-weight: bold;
        }

        .tabel-leger tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* FOOTER TTD */
        .footer-ttd {
            margin-top: 24px;
            display: flex;
            justify-content: space-between;
        }

        .ttd-box {
            text-align: center;
            width: 220px;
        }

        .ttd-box .ttd-label {
            font-size: 9pt;
            margin-bottom: 4px;
        }

        .ttd-box .ttd-jabatan {
            font-weight: bold;
            font-size: 9pt;
        }

        .ttd-box .ttd-ruang {
            height: 75px;
            position: relative;
        }

        .stempel-img { height: 75px; width: auto; position: absolute; left: 0px; top: -5px; z-index: 1; }
        .ttd-img { height: 60px; width: auto; position: absolute; left: 35px; top: 5px; z-index: 2; }

        .ttd-box .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            font-size: 9pt;
        }

        .ttd-box .ttd-nip {
            font-size: 8pt;
        }

        /* Selector semester untuk preview */
        .semester-switch {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
        }

        .semester-switch a {
            padding: 5px 14px;
            border-radius: 6px;
            font-family: sans-serif;
            font-size: 12px;
            text-decoration: none;
            font-weight: bold;
        }

        .semester-switch a.active {
            background: #2563eb;
            color: #fff;
        }

        .semester-switch a:not(.active) {
            background: #e2e8f0;
            color: #334155;
        }

        @media print {
            body { margin: 0; }
            .page { 
                margin-top: 10px;
                margin-right: {{ $settings['margin_right'] ?? '1.5' }}cm;
                margin-bottom: 0px;
                margin-left: {{ $settings['margin_left'] ?? '1.5' }}cm;
                width: auto;
                min-height: auto;
                padding: 0;
            }
            .no-print { display: none !important; }
        }

        @page {
            size: A4 landscape;
            margin-top: {{ $settings['margin_top'] ?? '1.5' }}cm;
            margin-bottom: {{ $settings['margin_bottom'] ?? '1.5' }}cm;
            margin-left: 0;
            margin-right: 0;
        }
    </style>
</head>
<body>

    {{-- Tombol aksi (hanya saat preview HTML) --}}
    @if(request('preview'))
    <div class="no-print" style="position: fixed; top: 12px; right: 12px; z-index: 100; display: flex; gap: 8px; align-items: center;">
        <a href="{{ request()->fullUrlWithQuery(['preview' => null]) }}"
           style="background:#2563eb;color:#fff;padding:8px 18px;border-radius:8px;text-decoration:none;font-family:sans-serif;font-size:13px;font-weight:bold;">
            Unduh PDF
        </a>
        <button onclick="window.print()"
                style="background:#16a34a;color:#fff;padding:8px 18px;border-radius:8px;border:none;cursor:pointer;font-family:sans-serif;font-size:13px;font-weight:bold;">
            Cetak
        </button>
    </div>

    {{-- Switch semester --}}
    <div class="no-print semester-switch" style="margin: 12px 0 0 12px;">
        <span style="font-family:sans-serif;font-size:12px;font-weight:bold;line-height:28px;">Semester:</span>
        <a href="{{ request()->fullUrlWithQuery(['semester' => 1]) }}"
           class="{{ $semester == 1 ? 'active' : '' }}">1</a>
        <a href="{{ request()->fullUrlWithQuery(['semester' => 2]) }}"
           class="{{ $semester == 2 ? 'active' : '' }}">2</a>
    </div>
    @endif

    <div class="page">

        {{-- HEADER --}}
        @php
            $jenjang    = $settings['jenjang_pendidikan'] ?? 'SD';
            $namaSekolah = $settings['sekolah_nama'] ?? 'Nama Sekolah';
            $kepsekNama  = $settings['kepsek_nama'] ?? '...';
            $kepsekNip   = $settings['kepsek_nip']  ?? '';
            $tahunPel    = $rombel->tahunPelajaran;

            if (!function_exists('getMapelSingkatan')) {
                function getMapelSingkatan($nama) {
                    $namaLower = strtolower(trim($nama));
                    $map = [
                        'bahasa indonesia' => 'BINDO',
                        'pendidikan agama islam dan budi pekerti' => 'PAIBP',
                        'pendidikan agama islam' => 'PAI',
                        'pendidikan kewarganegaraan' => 'PKN',
                        'pendidikan pancasila dan kewarganegaraan' => 'PPKN',
                        'pendidikan pancasila' => 'PP',
                        'matematika' => 'MTK',
                        'ilmu pengetahuan alam' => 'IPA',
                        'ilmu pengetahuan sosial' => 'IPS',
                        'bahasa inggris' => 'BING',
                        'pendidikan jasmani olahraga dan kesehatan' => 'PJOK',
                        'seni budaya dan prakarya' => 'SBDP',
                        'seni budaya' => 'SB',
                        'bahasa lampung' => 'BLPG',
                        'muatan lokal' => 'MULOK',
                        'pendidikan agama kristen dan budi pekerti' => 'PAKBP',
                        'pendidikan jasmani, olahraga, dan kesehatan' => 'PJOK',
                        'prakarya' => 'PRAK',
                    ];
                    
                    if (isset($map[$namaLower])) {
                        return $map[$namaLower];
                    }

                    $words = explode(' ', $nama);
                    $singkatan = '';
                    foreach ($words as $w) {
                        $w = trim($w);
                        if (strlen($w) > 0 && strtolower($w) != 'dan') {
                            $singkatan .= strtoupper($w[0]);
                        }
                    }
                    
                    if (strlen($singkatan) == 1 && strlen($nama) > 2) {
                        return strtoupper(substr($nama, 0, 3));
                    }

                    return $singkatan ?: '-';
                }
            }
        @endphp

        <div class="header">
            <div class="judul-utama">Leger Nilai Siswa</div>
            <div class="sub-judul">{{ $jenjang }} {{ $namaSekolah }}</div>
            <div class="info-rombel">
                Kelas / Rombel: <strong>{{ $rombel->nama }}</strong>
                &nbsp;&bull;&nbsp; Tingkat: <strong>{{ $rombel->tingkat }}</strong>
                &nbsp;&bull;&nbsp; Semester: <strong>{{ $semester }}</strong>
                &nbsp;&bull;&nbsp; Tahun Pelajaran: <strong>{{ $tahunPel ? $tahunPel->tahun : '-' }}</strong>
            </div>
        </div>

        {{-- TABEL --}}
        <table class="tabel-leger">
            <thead>
                <tr>
                    <th class="no">No</th>
                    <th style="text-align:left; padding-left:5px;">Nama Siswa</th>
                    <th>NISN</th>
                    {{-- Kolom mata pelajaran --}}
                    @foreach($mataPelajarans as $mapel)
                    <th style="font-size:6.5pt;">
                        {{ getMapelSingkatan($mapel->nama) }}
                    </th>
                    @endforeach
                    <th>Jml</th>
                    <th>Rata<br>-rata</th>
                    <th>Peri-<br>ngkat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataSiswa as $index => $row)
                @php
                    $siswa    = $row['siswa'];
                    $prestasi = $row['prestasi'];
                    $nilaiMap = $row['nilaiMap'];
                @endphp
                <tr>
                    <td class="no">{{ $index + 1 }}</td>
                    <td class="nama-siswa">{{ $siswa->nama }}</td>
                    <td class="nisn">{{ $siswa->nisn ?? '-' }}</td>
                    @foreach($mataPelajarans as $mapel)
                    <td class="nilai">
                        @if($nilaiMap->has($mapel->id))
                            {{ number_format((float) $nilaiMap[$mapel->id]->nilai, 0) }}
                        @else
                            -
                        @endif
                    </td>
                    @endforeach
                    <td class="jumlah">
                        {{ $prestasi ? number_format((float) $prestasi->jumlah_nilai, 0) : '-' }}
                    </td>
                    <td class="rata">
                        {{ $prestasi ? number_format((float) $prestasi->rata_rata, 1) : '-' }}
                    </td>
                    <td class="peringkat">
                        {{ $prestasi && $prestasi->peringkat ? $prestasi->peringkat : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ 3 + $mataPelajarans->count() + 3 }}" style="padding: 16px; text-align:center; color:#64748b;">
                        Tidak ada data siswa di rombel ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- KETERANGAN SINGKATAN MAPEL --}}
        @if($mataPelajarans->count())
        <div style="margin-top: 12px; font-size: 7.5pt; color: #334155;">
            <strong>Keterangan Singkatan Mata Pelajaran:</strong>
            <table style="width: 100%; border: none; margin-top: 4px; font-size: 7.5pt;">
                <tbody>
                    @php 
                        $cols = 3; 
                        $items = $mataPelajarans->values();
                        $rows = ceil($items->count() / $cols);
                    @endphp
                    @for($r = 0; $r < $rows; $r++)
                    <tr>
                        @for($c = 0; $c < $cols; $c++)
                            @php $idx = $r + ($c * $rows); @endphp
                            <td style="border: none; padding: 2px 4px 2px {{ $c == 0 ? '0' : '4px' }}; width: 33%; text-align: left; vertical-align: top;">
                                @if(isset($items[$idx]))
                                    <strong>{{ getMapelSingkatan($items[$idx]->nama) }}</strong> : {{ $items[$idx]->nama }}
                                @endif
                            </td>
                        @endfor
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        @endif

        {{-- FOOTER TTD --}}
        <table style="width: 100%; border: none; margin-top: 24px; page-break-inside: avoid;">
            <tr>
                <td style="width: 50%; vertical-align: top; text-align: left;">
                    {{-- Wali Kelas --}}
                    <div class="ttd-box" style="display: inline-block; text-align: center; width: 250px;">
                        <div class="ttd-label">Mengetahui,</div>
                        <div class="ttd-jabatan">Wali Kelas {{ $rombel->nama }}</div>
                        <div class="ttd-ruang"></div>
                        <div class="ttd-nama">_______________________</div>
                        <div class="ttd-nip">NIP. -</div>
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top; text-align: right;">
                    {{-- Kepala Sekolah --}}
                    <div class="ttd-box" style="display: inline-block; text-align: center; width: 250px;">
                        <div class="ttd-label">
                            {{ $settings['buku_induk_kota'] ?? '....' }},
                            {{ !empty($settings['buku_induk_tanggal']) ? $settings['buku_induk_tanggal'] : \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                        </div>
                        <div class="ttd-jabatan">Kepala {{ $jenjang }}</div>
                        <div class="ttd-jabatan">{{ $namaSekolah }}</div>
                        <div class="ttd-ruang">
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
                        <div class="ttd-nama">{{ $kepsekNama }}</div>
                        @if($kepsekNip)
                        <div class="ttd-nip">NIP. {{ $kepsekNip }}</div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

    </div>
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("Arial", "italic");
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
