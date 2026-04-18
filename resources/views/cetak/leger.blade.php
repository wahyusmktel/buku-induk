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
            width: 297mm;
            min-height: 210mm;
            margin: 0 auto;
            padding: 12mm 10mm 15mm 15mm;
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
            font-size: 8pt;
        }

        .tabel-leger th,
        .tabel-leger td {
            border: 1px solid #333;
            padding: 4px 3px;
            text-align: center;
            vertical-align: middle;
        }

        .tabel-leger thead th {
            background-color: #dbeafe;
            font-weight: bold;
            font-size: 7.5pt;
        }

        .tabel-leger tbody td.no {
            width: 22px;
        }

        .tabel-leger tbody td.nama-siswa {
            text-align: left;
            padding-left: 5px;
            white-space: nowrap;
            min-width: 120px;
        }

        .tabel-leger tbody td.nisn {
            font-size: 7pt;
            min-width: 80px;
        }

        .tabel-leger tbody td.nilai {
            min-width: 28px;
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
            height: 65px;
        }

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
            .page { margin: 0; padding: 8mm 8mm 10mm 12mm; }
            .no-print { display: none !important; }
        }

        @page {
            size: A4 landscape;
            margin: 0;
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
                    <th rowspan="2" class="no">No</th>
                    <th rowspan="2" style="min-width:120px; text-align:left; padding-left:5px;">Nama Siswa</th>
                    <th rowspan="2" style="min-width:80px;">NISN</th>
                    {{-- Kolom mata pelajaran --}}
                    @foreach($mataPelajarans as $mapel)
                    <th style="min-width:28px; font-size:7pt;">
                        {{-- Singkat nama mapel agar muat --}}
                        {{ \Illuminate\Support\Str::limit($mapel->nama, 8, '') }}
                    </th>
                    @endforeach
                    <th rowspan="2" style="min-width:36px;">Jml</th>
                    <th rowspan="2" style="min-width:36px;">Rata<br>-rata</th>
                    <th rowspan="2" style="min-width:30px;">Peri-<br>ngkat</th>
                </tr>
                <tr>
                    @foreach($mataPelajarans as $mapel)
                    <th style="font-size:6.5pt; font-weight:normal; color:#1e40af;">{{ $mapel->urutan }}</th>
                    @endforeach
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
        <div style="margin-top: 8px; font-size: 7.5pt; color: #334155;">
            <strong>Keterangan:</strong>
            @foreach($mataPelajarans as $i => $mapel)
                {{ $mapel->urutan }}={{ $mapel->nama }}{{ !$loop->last ? ';' : '' }}
            @endforeach
        </div>
        @endif

        {{-- FOOTER TTD --}}
        <div class="footer-ttd">
            {{-- Wali Kelas --}}
            <div class="ttd-box">
                <div class="ttd-label">Mengetahui,</div>
                <div class="ttd-jabatan">Wali Kelas {{ $rombel->nama }}</div>
                <div class="ttd-ruang"></div>
                <div class="ttd-nama">_______________________</div>
                <div class="ttd-nip">NIP. -</div>
            </div>

            {{-- Kepala Sekolah --}}
            <div class="ttd-box">
                <div class="ttd-label">
                    {{ $settings['buku_induk_kota'] ?? '....' }},
                    {{ !empty($settings['buku_induk_tanggal']) ? $settings['buku_induk_tanggal'] : \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </div>
                <div class="ttd-jabatan">Kepala {{ $jenjang }}</div>
                <div class="ttd-jabatan">{{ $namaSekolah }}</div>
                <div class="ttd-ruang"></div>
                <div class="ttd-nama">{{ $kepsekNama }}</div>
                @if($kepsekNip)
                <div class="ttd-nip">NIP. {{ $kepsekNip }}</div>
                @endif
            </div>
        </div>

    </div>
</body>
</html>
