@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('header_title', 'Dashboard Induk Siswa')
@section('breadcrumb', 'Ikhtisar Sistem')

@section('content')
<!-- Hero Welcome Section -->
<div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl shadow-xl p-8 mb-6 relative overflow-hidden text-white">
    <div class="relative z-10">
        <h2 class="text-3xl font-extrabold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
        <p class="text-indigo-100 font-medium max-w-xl">Halaman dashboard telah dioptimalkan untuk performa maksimal. Kelola data induk siswa dengan cepat dan efisien.</p>
        <div class="flex flex-wrap gap-3 mt-6">
            <a href="{{ route('siswas.index') }}" class="bg-white/20 hover:bg-white/30 backdrop-blur-md px-6 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 border border-white/10">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                Data Siswa
            </a>
            <a href="{{ route('buku-induk.index') }}" class="bg-indigo-500 hover:bg-indigo-400 px-6 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 shadow-lg shadow-indigo-900/20">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg>
                Buku Induk
            </a>
        </div>
    </div>
    <!-- Decorative Elements -->
    <img src="{{ asset('images/batik-kawung-motif.png') }}" class="absolute -right-4 -bottom-4 w-72 opacity-20 pointer-events-none" style="mask-image: linear-gradient(to bottom right, black, transparent); -webkit-mask-image: linear-gradient(to bottom right, black, transparent);">
    <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute right-10 top-10 w-20 h-20 bg-indigo-400/20 rounded-full blur-2xl animate-pulse"></div>
</div>

{{-- Aksi Cepat: Tahun Pelajaran (Super Admin Only) --}}
@hasrole('Super Admin')
<div class="mb-6 bg-gradient-to-r from-indigo-50 via-violet-50 to-purple-50 rounded-2xl border border-indigo-100 shadow-sm overflow-hidden">
    <div class="flex flex-col md:flex-row md:items-center gap-4 p-5">
        {{-- Icon & Title --}}
        <div class="flex items-center gap-3 md:min-w-[180px]">
            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl text-white shadow-md shadow-indigo-200">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Aksi Cepat</h3>
                <p class="text-xs text-slate-500">Tahun Pelajaran</p>
            </div>
        </div>

        {{-- Divider --}}
        <div class="hidden md:block w-px h-10 bg-indigo-200/60"></div>

        {{-- Status TP Aktif --}}
        <div class="flex-shrink-0">
            @if($tahunAktif)
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-bold text-emerald-700">{{ $tahunAktif->tahun }} - Semester {{ $tahunAktif->semester }}</span>
                </div>
            @else
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 rounded-xl">
                    <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                    <span class="text-sm font-bold text-amber-700">Belum ada TP aktif</span>
                </div>
            @endif
        </div>

        {{-- Divider --}}
        <div class="hidden md:block w-px h-10 bg-indigo-200/60"></div>

        {{-- Form Beralih --}}
        <form action="#" method="POST" id="quick-activate-form" class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            @csrf
            @method('PATCH')
            <div class="relative flex-1">
                <select name="tahun_id" id="tahun-select" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none appearance-none cursor-pointer shadow-sm hover:border-indigo-300 transition-colors">
                    <option value="" disabled selected>Pilih Tahun Pelajaran</option>
                    @foreach($tahunPelajarans as $tp)
                        <option value="{{ $tp->id }}" {{ $tahunAktif && $tahunAktif->id == $tp->id ? 'disabled' : '' }}>{{ $tp->tahun }} - Semester {{ $tp->semester }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>
                </div>
            </div>
            <button type="button" id="btn-activate-tp" class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-bold py-2.5 px-6 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap" disabled>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                Aktifkan
            </button>
        </form>
    </div>
</div>
@endhasrole

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow group">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Siswa Aktif</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalSiswaAktif, 0, ',', '.') }}</h3>
        </div>
        <div class="w-12 h-12 bg-sky-50 rounded-xl flex items-center justify-center text-sky-600 group-hover:bg-sky-600 group-hover:text-white transition-colors duration-300">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow group">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Alumni</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalAlumni, 0, ',', '.') }}</h3>
        </div>
        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow group">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Rombel</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalRombel }}</h3>
        </div>
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </div>
    </div>
</div>

{{-- Warning Badges --}}
@if($rombelTanpaAnggota > 0 || $bukuIndukKurang > 0)
<div class="flex flex-wrap gap-3 mb-6">
    @if($rombelTanpaAnggota > 0)
    <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 rounded-xl text-sm font-semibold text-amber-700">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        Ada {{ $rombelTanpaAnggota }} rombel tanpa anggota
    </div>
    @endif
    @if($bukuIndukKurang > 0)
    <div class="flex items-center gap-2 px-4 py-2 bg-sky-50 border border-sky-200 rounded-xl text-sm font-semibold text-sky-700">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ $bukuIndukKurang }} buku induk belum lengkap foto
    </div>
    @endif
</div>
@endif

{{-- Distribusi Siswa per Tingkat --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
    <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-indigo-500 inline-block"></span>
        Distribusi Siswa Per Tingkat (Tahun Aktif)
    </h3>
    @if($siswaPerTingkat->isEmpty())
        <p class="text-sm text-slate-400 text-center py-4">Tidak ada data siswa aktif.</p>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="text-left py-2 px-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Tingkat</th>
                    <th class="text-center py-2 px-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Total</th>
                    <th class="text-center py-2 px-3 text-xs font-bold text-sky-500 uppercase tracking-wider">L</th>
                    <th class="text-center py-2 px-3 text-xs font-bold text-rose-500 uppercase tracking-wider">P</th>
                    <th class="text-left py-2 px-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Proporsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($siswaPerTingkat as $row)
                @php $pct = $totalSiswaAktif > 0 ? round($row->total / $totalSiswaAktif * 100) : 0; @endphp
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-2.5 px-3 font-bold text-slate-700">Kelas {{ $row->tingkat_kelas }}</td>
                    <td class="py-2.5 px-3 text-center font-black text-indigo-700">{{ $row->total }}</td>
                    <td class="py-2.5 px-3 text-center text-sky-600 font-semibold">{{ $row->laki }}</td>
                    <td class="py-2.5 px-3 text-center text-rose-500 font-semibold">{{ $row->perempuan }}</td>
                    <td class="py-2.5 px-3">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-slate-500 w-8 text-right">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- Tren Siswa per Tahun Pelajaran (Chart.js) --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
    <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-indigo-500 inline-block"></span>
        Tren Jumlah Siswa per Tahun Pelajaran
    </h3>
    @if($trendPerTahun->isEmpty())
        <p class="text-sm text-slate-400 text-center py-4">Belum ada data tahun pelajaran.</p>
    @else
    <canvas id="chartTren" height="100"></canvas>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('chartTren');
    if (!canvas) return;
    const trendData = @json($trendPerTahun);
    const labels = trendData.map(t => t.tahun + ' - ' + t.semester);
    const aktifData = trendData.map(t => t.siswa_aktif);
    const lulusData = trendData.map(t => t.siswa_lulus);
    new Chart(canvas, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Siswa Aktif',
                    data: aktifData,
                    borderColor: '#6366f1',
                    backgroundColor: '#6366f120',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointRadius: 4,
                },
                {
                    label: 'Alumni/Lulus',
                    data: lulusData,
                    borderColor: '#10b981',
                    backgroundColor: '#10b98120',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
});
</script>

<!-- Tips Navigasi -->
<div class="bg-sky-50 rounded-2xl p-6 border border-sky-100 flex gap-4 items-start">
    <div class="p-3 bg-sky-500 rounded-xl text-white">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    </div>
    <div>
        <h4 class="text-sky-900 font-bold mb-1">Tips Navigasi</h4>
        <p class="text-sky-700 text-sm leading-relaxed">Gunakan menu di samping untuk mengakses fitur lengkap. Dashboard ini sengaja dibuat minimalis untuk memastikan kecepatan akses data yang optimal bagi manajemen sekolah.</p>
    </div>
</div>

@hasrole('Super Admin')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('tahun-select');
    const btn = document.getElementById('btn-activate-tp');
    const form = document.getElementById('quick-activate-form');

    if (!select || !btn || !form) return;

    select.addEventListener('change', function() {
        btn.disabled = !this.value;
    });

    btn.addEventListener('click', function() {
        const selectedId = select.value;
        const selectedText = select.options[select.selectedIndex].text;

        if (!selectedId) return;

        Swal.fire({
            title: 'Konfirmasi Perubahan',
            html: '<div class="text-left">' +
                '<p class="text-slate-600 mb-3">Apakah Anda yakin ingin beralih ke tahun pelajaran:</p>' +
                '<div class="bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-3 text-center">' +
                '<span class="text-base font-bold text-indigo-700">' + selectedText + '</span>' +
                '</div>' +
                '<p class="text-xs text-slate-400 mt-3">Data yang ditampilkan akan disesuaikan dengan tahun pelajaran yang dipilih.</p>' +
                '</div>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Aktifkan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            focusCancel: true,
        }).then((result) => {
            if (result.isConfirmed) {
                form.action = '/tahun-pelajaran/' + selectedId + '/activate';
                form.submit();
            } else {
                select.value = '';
                btn.disabled = true;
            }
        });
    });
});
</script>
@endhasrole
@endsection
