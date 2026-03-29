<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Induk — {{ $siswa->nama }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; background: white; }
        @page { size: A4; margin: 1.5cm 1.5cm 1.5cm 2cm; }
        @media print {
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
        h1 { font-size: 16pt; font-weight: bold; text-align: center; text-transform: uppercase; letter-spacing: 1px; }
        h2 { font-size: 13pt; font-weight: bold; text-align: center; text-transform: uppercase; }
        .school-header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 15px; }
        .school-header p { font-size: 10pt; }
        .section-title { font-weight: bold; font-size: 11pt; text-transform: uppercase; text-decoration: underline; margin: 15px 0 8px; }
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.data-table td { padding: 3px 6px; vertical-align: top; }
        table.data-table td:first-child { width: 38%; }
        table.data-table td:nth-child(2) { width: 4%; text-align: center; }
        table.data-table td:nth-child(3) { font-weight: bold; }
        table.bordered { width: 100%; border-collapse: collapse; font-size: 9pt; }
        table.bordered th, table.bordered td { border: 1px solid #000; padding: 3px 5px; text-align: center; }
        table.bordered th { background-color: #e0e0e0; font-weight: bold; }
        table.bordered td.left { text-align: left; }
        .signature-area { display: flex; justify-content: flex-end; margin-top: 30px; }
        .signature-box { text-align: center; min-width: 200px; }
        .signature-line { border-bottom: 1px solid #000; margin: 50px auto 5px; width: 180px; }
        .print-btn { position: fixed; top: 20px; right: 20px; padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 10px; font-family: Arial; font-size: 13px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 12px rgba(79,70,229,0.3); }
    </style>
</head>
<body>

    <button class="print-btn no-print" onclick="window.print()">🖨️ Cetak Buku Induk</button>

    {{-- PAGE 1: Identity --}}
    <div class="school-header">
        <h1>Buku Induk Siswa</h1>
        <h2>Sekolah Dasar</h2>
        <p>Tahun Pelajaran {{ $siswa->rombel_saat_ini ?? '' }}</p>
    </div>

    <p class="section-title">I. Keterangan Murid</p>
    <table class="data-table">
        <tr><td>Nomor Induk</td><td>:</td><td>{{ $bukuInduk->no_induk ?? '___________' }}</td></tr>
        <tr><td>Nama Lengkap</td><td>:</td><td>{{ $siswa->nama }}</td></tr>
        <tr><td>Nama Panggilan</td><td>:</td><td>{{ $bukuInduk->nama_panggilan ?? '___________' }}</td></tr>
        <tr><td>Jenis Kelamin</td><td>:</td><td>{{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
        <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') : '—' }}</td></tr>
        <tr><td>Agama</td><td>:</td><td>{{ $siswa->agama ?? '—' }}</td></tr>
        <tr><td>Kewarganegaraan</td><td>:</td><td>{{ $bukuInduk->kewarganegaraan ?? 'WNI' }}</td></tr>
        <tr><td>Anak ke</td><td>:</td><td>{{ $siswa->anak_ke_berapa ?? '—' }}</td></tr>
        <tr><td>Jumlah Saudara (Kandung/Tiri/Angkat)</td><td>:</td><td>{{ $siswa->jml_saudara_kandung ?? 0 }} / {{ $bukuInduk->jml_saudara_tiri ?? 0 }} / {{ $bukuInduk->jml_saudara_angkat ?? 0 }}</td></tr>
        <tr><td>Bahasa Sehari-hari</td><td>:</td><td>{{ $bukuInduk->bahasa_sehari_hari ?? '—' }}</td></tr>
        <tr><td>Golongan Darah</td><td>:</td><td>{{ $bukuInduk->golongan_darah ?? '—' }}</td></tr>
        <tr><td>Penyakit Pernah Diderita</td><td>:</td><td>{{ $bukuInduk->riwayat_penyakit ?? '—' }}</td></tr>
        <tr><td>Berat / Tinggi Badan</td><td>:</td><td>{{ $siswa->berat_badan ?? '—' }} kg / {{ $siswa->tinggi_badan ?? '—' }} cm</td></tr>
        <tr><td>Alamat Tempat Tinggal</td><td>:</td><td>{{ $siswa->alamat }}, RT {{ $siswa->rt }}/RW {{ $siswa->rw }}, {{ $siswa->kelurahan }}, {{ $siswa->kecamatan }}</td></tr>
        <tr><td>No. Telepon / HP</td><td>:</td><td>{{ $siswa->hp ?? $siswa->telepon ?? '—' }}</td></tr>
        <tr><td>Bertempat Tinggal Dengan</td><td>:</td><td>{{ $bukuInduk->bertempat_tinggal_dengan ?? '—' }}</td></tr>
        <tr><td>Jarak ke Sekolah</td><td>:</td><td>{{ $siswa->jarak_rumah_ke_sekolah_km ?? '—' }} km</td></tr>
        <tr><td>Alat Transportasi</td><td>:</td><td>{{ $siswa->alat_transportasi ?? '—' }}</td></tr>
    </table>

    <p class="section-title">II. Keterangan Orang Tua</p>
    <table class="data-table">
        <tr><td colspan="3"><strong>A. Ayah Kandung</strong></td></tr>
        <tr><td>Nama Ayah</td><td>:</td><td>{{ $siswa->nama_ayah ?? '—' }}</td></tr>
        <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{ $bukuInduk->tempat_lahir_ayah ?? '—' }}, {{ $bukuInduk->tanggal_lahir_ayah?->format('d F Y') ?? ($siswa->tahun_lahir_ayah ?? '—') }}</td></tr>
        <tr><td>Agama</td><td>:</td><td>{{ $bukuInduk->agama_ayah ?? '—' }}</td></tr>
        <tr><td>Kewarganegaraan</td><td>:</td><td>{{ $bukuInduk->kewarganegaraan_ayah ?? 'WNI' }}</td></tr>
        <tr><td>Pendidikan Terakhir</td><td>:</td><td>{{ $siswa->jenjang_pendidikan_ayah ?? '—' }}</td></tr>
        <tr><td>Pekerjaan</td><td>:</td><td>{{ $siswa->pekerjaan_ayah ?? '—' }}</td></tr>
        <tr><td>Penghasilan / Tahun</td><td>:</td><td>{{ $siswa->penghasilan_ayah ?? '—' }}</td></tr>
        <tr><td colspan="3"><br/><strong>B. Ibu Kandung</strong></td></tr>
        <tr><td>Nama Ibu</td><td>:</td><td>{{ $siswa->nama_ibu ?? '—' }}</td></tr>
        <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{ $bukuInduk->tempat_lahir_ibu ?? '—' }}, {{ $bukuInduk->tanggal_lahir_ibu?->format('d F Y') ?? ($siswa->tahun_lahir_ibu ?? '—') }}</td></tr>
        <tr><td>Agama</td><td>:</td><td>{{ $bukuInduk->agama_ibu ?? '—' }}</td></tr>
        <tr><td>Kewarganegaraan</td><td>:</td><td>{{ $bukuInduk->kewarganegaraan_ibu ?? 'WNI' }}</td></tr>
        <tr><td>Pendidikan Terakhir</td><td>:</td><td>{{ $siswa->jenjang_pendidikan_ibu ?? '—' }}</td></tr>
        <tr><td>Pekerjaan</td><td>:</td><td>{{ $siswa->pekerjaan_ibu ?? '—' }}</td></tr>
        <tr><td>Penghasilan / Tahun</td><td>:</td><td>{{ $siswa->penghasilan_ibu ?? '—' }}</td></tr>
    </table>

    <p class="section-title">III. Perkembangan Murid</p>
    <table class="data-table">
        <tr><td>Asal Masuk Sekolah</td><td>:</td><td>{{ $bukuInduk->asal_masuk_sekolah ?? '—' }}</td></tr>
        <tr><td>Nama TK / Paud Asal</td><td>:</td><td>{{ $bukuInduk->nama_tk_asal ?? '—' }}</td></tr>
        <tr><td>Sekolah Asal</td><td>:</td><td>{{ $siswa->sekolah_asal ?? '—' }}</td></tr>
        <tr><td>Tanggal Masuk</td><td>:</td><td>{{ $bukuInduk->tgl_masuk_sekolah?->format('d F Y') ?? '—' }}</td></tr>
        <tr><td>Pindahan dari</td><td>:</td><td>{{ $bukuInduk->pindah_dari ?? '—' }}</td></tr>
        <tr><td>Masuk di Kelas</td><td>:</td><td>{{ $bukuInduk->kelas_pindah_masuk ?? '—' }}</td></tr>
    </table>

    {{-- PAGE 2: Academic Records --}}
    <div class="page-break">
        <p class="section-title">IV. Prestasi Belajar</p>
        <table class="bordered">
            <thead>
                <tr>
                    <th rowspan="2">Kls</th>
                    <th rowspan="2">Smt</th>
                    <th rowspan="2">Thn Pelajaran</th>
                    <th colspan="9">Nilai Mata Pelajaran</th>
                    <th rowspan="2">Jml</th>
                    <th rowspan="2">Rata²</th>
                    <th rowspan="2">Rank</th>
                    <th colspan="3">Kehadiran</th>
                    <th rowspan="2">Naik?</th>
                </tr>
                <tr>
                    <th>Agm</th><th>PKn</th><th>BI</th><th>MTK</th>
                    <th>IPA</th><th>IPS</th><th>SBK</th><th>PJOK</th><th>ML</th>
                    <th>S</th><th>I</th><th>A</th>
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
                    <td class="left" style="font-size:8pt;">{{ $p?->tahun_pelajaran ?? '' }}</td>
                    <td>{{ $p?->nilai_agama ?? '' }}</td>
                    <td>{{ $p?->nilai_pkn ?? '' }}</td>
                    <td>{{ $p?->nilai_bindo ?? '' }}</td>
                    <td>{{ $p?->nilai_mtk ?? '' }}</td>
                    <td>{{ $p?->nilai_ipa ?? '' }}</td>
                    <td>{{ $p?->nilai_ips ?? '' }}</td>
                    <td>{{ $p?->nilai_sbk ?? '' }}</td>
                    <td>{{ $p?->nilai_pjok ?? '' }}</td>
                    <td>{{ $p?->nilai_mulok ?? '' }}</td>
                    <td><strong>{{ $p?->jumlah_nilai ?? '' }}</strong></td>
                    <td>{{ $p?->rata_rata ?? '' }}</td>
                    <td>{{ $p?->peringkat ?? '' }}</td>
                    <td>{{ $p?->hadir_sakit ?? '' }}</td>
                    <td>{{ $p?->hadir_izin ?? '' }}</td>
                    <td>{{ $p?->hadir_alpha ?? '' }}</td>
                    <td style="font-size:8pt;">{{ ($p && $semester == 2) ? ($p->keterangan_kenaikan ?? '') : '' }}</td>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>

        <br/>
        <p class="section-title">V. Tamat Belajar / Meninggalkan Sekolah</p>
        <table class="data-table">
            <tr><td>Tanggal Lulus</td><td>:</td><td>{{ $bukuInduk->tgl_lulus?->format('d F Y') ?? '—' }}</td></tr>
            <tr><td>Nomor Ijazah / STTB</td><td>:</td><td>{{ $bukuInduk->no_ijazah ?? $siswa->no_seri_ijazah ?? '—' }}</td></tr>
            <tr><td>No. Peserta UN</td><td>:</td><td>{{ $siswa->no_peserta_un ?? '—' }}</td></tr>
            <tr><td>Melanjutkan ke</td><td>:</td><td>{{ $bukuInduk->lanjut_ke ?? '—' }}</td></tr>
            <tr><td>Tanggal Keluar</td><td>:</td><td>{{ $bukuInduk->tgl_keluar?->format('d F Y') ?? '—' }}</td></tr>
            <tr><td>Alasan Keluar</td><td>:</td><td>{{ $bukuInduk->alasan_keluar ?? '—' }}</td></tr>
            <tr><td>Catatan Beasiswa</td><td>:</td><td>{{ $bukuInduk->beasiswa ?? '—' }}</td></tr>
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

</body>
</html>
