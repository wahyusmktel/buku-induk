<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Aktif - {{ $siswa->nama }}</title>
    <style>
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
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm 25mm 20mm 30mm;
        }

        /* KOP SURAT */
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
        .ttd-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .ttd-block {
            text-align: center;
            width: 260px;
        }

        .ttd-block .ttd-kota-tgl {
            margin-bottom: 6px;
        }

        .ttd-block .ttd-jabatan {
            font-weight: bold;
        }

        .ttd-block .ttd-ruang {
            height: 80px;
        }

        .ttd-block .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }

        .ttd-block .ttd-nip {
            font-size: 10.5pt;
        }

        /* PRINT STYLES */
        @media print {
            body {
                margin: 0;
            }
            .page {
                margin: 0;
                padding: 15mm 20mm 15mm 25mm;
            }
            .no-print {
                display: none !important;
            }
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }
    </style>
</head>
<body>

    {{-- Tombol aksi (hanya saat preview HTML) --}}
    @if(request('preview'))
    <div class="no-print" style="position: fixed; top: 12px; right: 12px; z-index: 100; display: flex; gap: 8px;">
        <a href="{{ request()->fullUrlWithQuery(['preview' => null]) }}"
           style="background:#2563eb;color:#fff;padding:8px 18px;border-radius:8px;text-decoration:none;font-family:sans-serif;font-size:13px;font-weight:bold;">
            Unduh PDF
        </a>
        <button onclick="window.print()"
                style="background:#16a34a;color:#fff;padding:8px 18px;border-radius:8px;border:none;cursor:pointer;font-family:sans-serif;font-size:13px;font-weight:bold;">
            Cetak
        </button>
    </div>
    @endif

    <div class="page">

        {{-- KOP SURAT --}}
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
        <div class="ttd-section">
            <div class="ttd-block">
                <div class="ttd-kota-tgl">
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
