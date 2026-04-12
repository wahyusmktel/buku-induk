<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Induk — {{ $siswa->nama }}</title>
    <style>
        @page { 
            margin-top: {{ $settings['margin_top'] ?? '2.5' }}cm;
            margin-bottom: {{ $settings['margin_bottom'] ?? '2.5' }}cm;
            margin-left: 0;
            margin-right: 0;
        }
        @page :first {
            margin-top: 0;
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
            margin-top: 5px;
            margin-right: {{ $settings['margin_right'] ?? '2.5' }}cm;
            margin-bottom: 0px; /* @page margin-bottom has handled the bottom spacing */
            margin-left: {{ $settings['margin_left'] ?? '2.5' }}cm;
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

        <div class="school-header pb-4 mb-4">
            <h1>Catatan Prestasi Belajar Siswa</h1>
            <h2>{{ $settings['sekolah_nama'] ?? 'Sekolah Dasar / Menengah' }}</h2>
            <p>Nama Siswa: <strong>{{ $siswa->nama }}</strong> | NISN: <strong>{{ $siswa->nisn ?? '-' }}</strong></p>
        </div>

        {{-- ===== VI. PRESTASI BELAJAR (halaman baru) ===== --}}
        <div>
            <p class="section-title" style="margin-top: 0; font-size: 14pt;">A. Penilaian Hasil Belajar</p>

            @php
                // Build tahun_pelajaran label per kelas (ambil dari Semester 1 jika ada, fallback Semester 2)
                $tahunPerKelas = [];
                for ($k = 1; $k <= 6; $k++) {
                    $rec = $akademikGrid[$k][1] ?? $akademikGrid[$k][2] ?? null;
                    $tahunPerKelas[$k] = $rec?->tahun_pelajaran ?? '';
                }
            @endphp

            <table class="bordered" style="font-size: 8.5pt; table-layout: fixed; width: 100%;">
                <colgroup>
                    <col style="width: 20pt;">  {{-- No --}}
                    <col> {{-- Mata Pelajaran --}}
                    @foreach(range(1,6) as $k)
                    <col style="width: 25pt;"> {{-- Smt I --}}
                    <col style="width: 25pt;"> {{-- Smt II --}}
                    @endforeach
                    <col style="width: 40pt;"> {{-- Nilai Ijazah --}}
                </colgroup>
                <thead>
                    {{-- ROW 1: Tahun Pelajaran --}}
                    <tr>
                        <th rowspan="4" style="vertical-align: middle;">No</th>
                        <th rowspan="4" style="vertical-align: middle; text-align: left; padding-left: 4px;">Mata Pelajaran</th>
                        @foreach(range(1,6) as $k)
                        <th colspan="2" style="font-size: 7pt;">{{ $tahunPerKelas[$k] ?: '— / —' }}</th>
                        @endforeach
                        <th rowspan="4" style="vertical-align: middle; font-size: 7pt;">Nilai Ijazah</th>
                    </tr>
                    {{-- ROW 2: Kelas --}}
                    <tr>
                        @foreach(range(1,6) as $k)
                        <th colspan="2">Kelas {{ $k }}</th>
                        @endforeach
                    </tr>
                    {{-- ROW 3: Smstr --}}
                    <tr>
                        @foreach(range(1,6) as $k)
                        <th colspan="2" style="font-size: 6.5pt; font-weight: normal;">Smstr</th>
                        @endforeach
                    </tr>
                    {{-- ROW 4: I / II --}}
                    <tr>
                        @foreach(range(1,6) as $k)
                        <th style="font-size: 7pt;">I</th>
                        <th style="font-size: 7pt;">II</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $rowNum = 0;
                        $mulokStarted = false;
                        $mulokSubIndex = 0; // a, b, c...
                    @endphp

                    @foreach($mataPelajarans as $mapel)
                        @if(empty(trim($mapel->nama))) @continue @endif
                        @php
                            $isMulokHeader = stripos($mapel->nama, 'Muatan Lokal') === 0
                                             && trim(str_ireplace('Muatan Lokal', '', $mapel->nama)) === '';
                            $isMulokSub   = $mulokStarted && !$isMulokHeader;
                            if ($isMulokHeader) {
                                $mulokStarted  = true;
                                $mulokSubIndex = 0;
                                $rowNum++;
                            } elseif (!$mulokStarted) {
                                $rowNum++;
                            }
                        @endphp
                        <tr>
                            <td style="text-align: center; font-size: 7.5pt;">{{ $isMulokSub ? '' : $rowNum }}</td>
                            <td class="left" style="font-size: 8.5pt; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; {{ $isMulokHeader ? 'font-weight:bold;' : '' }} {{ $isMulokSub ? 'padding-left: 10px;' : '' }}">
                                @if($isMulokSub)
                                    {{ chr(97 + $mulokSubIndex) }}. {{ $mapel->nama }}
                                    @php $mulokSubIndex++ @endphp
                                @else
                                    {{ $mapel->nama }}
                                @endif
                            </td>
                            @foreach(range(1,6) as $k)
                                @foreach([1,2] as $smt)
                                    @php
                                        $p   = $akademikGrid[$k][$smt] ?? null;
                                        $val = (!$isMulokHeader && $p)
                                                ? $p->nilais->where('mata_pelajaran_id', $mapel->id)->first()?->nilai
                                                : null;
                                    @endphp
                                    <td style="font-size: 7.5pt;">{{ $val !== null ? number_format($val, 0) : '' }}</td>
                                @endforeach
                            @endforeach
                            <td></td>
                        </tr>
                    @endforeach

                    {{-- Summary rows --}}
                    @php
                        $summaryRows = [
                            ['label' => 'Jumlah Nilai',       'field' => 'jumlah_nilai',       'fmt' => true],
                            ['label' => 'Nilai Rata-Rata',    'field' => 'rata_rata',           'fmt' => false],
                            ['label' => 'Peringkat Kelas ke', 'field' => 'peringkat',           'fmt' => false],
                            ['label' => 'Naik/Tidak Naik',   'field' => 'keterangan_kenaikan', 'fmt' => false, 'smt_only' => 2],
                        ];
                    @endphp
                    @foreach($summaryRows as $sr)
                        <tr style="background-color: #f3f4f6;">
                            <td></td>
                            <td class="left" style="font-weight: bold; font-size: 7.5pt; white-space: nowrap;">{{ $sr['label'] }}</td>
                            @foreach(range(1,6) as $k)
                                @foreach([1,2] as $smt)
                                    @php
                                        $p = $akademikGrid[$k][$smt] ?? null;
                                        $v = null;
                                        if ($p) {
                                            if (isset($sr['smt_only']) && $smt != $sr['smt_only']) {
                                                $v = null;
                                            } else {
                                                $raw = $p->{$sr['field']} ?? null;
                                                $v = ($sr['fmt'] && $raw !== null) ? number_format($raw, 0) : $raw;
                                            }
                                        }
                                    @endphp
                                    <td style="font-size: 7.5pt;">{{ $v ?? '' }}</td>
                                @endforeach
                            @endforeach
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        </div>

        <div style="margin-top: 15px;">
            <table style="width: 100%; border: none; border-collapse: collapse; margin-bottom: 20px;">
                <tr>
                    {{-- Grid Ekstrakurikuler --}}
                    @php
                        $studentEkskulIds = $siswa->prestasiEkstrakurikulers->pluck('ekstrakurikuler_id')->unique();
                        $activeEkskuls = $ekstrakurikulers->whereIn('id', $studentEkskulIds);
                    @endphp
                    @if($activeEkskuls->count() > 0)
                    <td style="vertical-align: top; padding-right: 10px;">
                        <p class="section-title" style="margin-top: 0; font-size: 8pt;">Tabel Ekstrakurikuler</p>
                        <table class="bordered">
                            <thead>
                                <tr>
                                    <th style="width: 25px;">Kls</th>
                                    <th style="width: 25px;">Smt</th>
                                    @foreach($activeEkskuls as $ekskul)
                                    <th>{{ $ekskul->nama_ekstrakurikuler }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(range(1, 6) as $kelas)
                                @foreach([1, 2] as $semester)
                                @php $eks = $siswa->prestasiEkstrakurikulers->where('kelas', $kelas)->where('semester', $semester); @endphp
                                <tr>
                                    @if($semester == 1)
                                    <td rowspan="2" style="vertical-align:middle; font-weight:bold;">{{ $kelas }}</td>
                                    @endif
                                    <td>{{ $semester }}</td>
                                    @foreach($activeEkskuls as $ekskul)
                                    <td>{{ $eks->where('ekstrakurikuler_id', $ekskul->id)->first()?->predikat ?? '' }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                    @endif

                    {{-- Grid Kepribadian --}}
                    <td style="vertical-align: top; padding-right: 10px;">
                        <p class="section-title" style="margin-top: 0; font-size: 8pt;">Tabel Kepribadian</p>
                        <table class="bordered">
                            <thead>
                                <tr>
                                    <th style="width: 25px;">Kls</th>
                                    <th style="width: 25px;">Smt</th>
                                    <th style="width: 40px;">Sikap</th>
                                    <th style="width: 45px;">Kerajinan</th>
                                    <th style="width: 40px;">Kerapian</th>
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
                                    <td>{{ $p?->sikap ?? '' }}</td>
                                    <td>{{ $p?->kerajinan ?? '' }}</td>
                                    <td>{{ $p?->kebersihan_kerapian ?? '' }}</td>
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </td>

                    {{-- Grid Kehadiran --}}
                    <td style="vertical-align: top;">
                        <p class="section-title" style="margin-top: 0; font-size: 8pt;">Tabel Ketidak Hadiran</p>
                        <table class="bordered">
                            <thead>
                                <tr>
                                    <th style="width: 25px;">Kls</th>
                                    <th style="width: 25px;">Smt</th>
                                    <th style="width: 30px;">Sakit</th>
                                    <th style="width: 30px;">Izin</th>
                                    <th style="width: 30px;">Alpha</th>
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
                                    <td>{{ $p?->hadir_sakit ?? '' }}</td>
                                    <td>{{ $p?->hadir_izin ?? '' }}</td>
                                    <td>{{ $p?->hadir_alpha ?? '' }}</td>
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
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
