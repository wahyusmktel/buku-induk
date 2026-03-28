@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('header_title', 'Dashboard Induk Siswa')
@section('breadcrumb', 'Ikhtisar Sistem')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Total Siswa Aktif</p>
            <h3 class="text-3xl font-extrabold text-slate-800">842</h3>
            <p class="text-xs font-semibold text-emerald-500 flex items-center gap-1 mt-2">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                +2.5% bulan ini
            </p>
        </div>
        <div class="w-12 h-12 bg-sky-50 rounded-xl flex items-center justify-center text-sky-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Alumni & Lulusan</p>
            <h3 class="text-3xl font-extrabold text-slate-800">3,214</h3>
            <p class="text-xs font-medium text-slate-400 mt-2">Total Sejak Tahun 2000</p>
        </div>
        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Rombongan Belajar</p>
            <h3 class="text-3xl font-extrabold text-slate-800">28</h3>
            <p class="text-xs font-medium text-slate-400 mt-2">Terbagi dalam 6 Jenjang</p>
        </div>
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Tenaga Pendidik</p>
            <h3 class="text-3xl font-extrabold text-slate-800">45</h3>
            <p class="text-xs font-medium text-slate-400 mt-2">Guru dan Staf TU</p>
        </div>
        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content List -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Siswa Pindahan Baru</h3>
            <a href="#" class="text-sm font-semibold text-sky-600 hover:text-sky-700">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/50 text-slate-400 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="py-4 px-6">Nama Siswa / NISN</th>
                        <th class="py-4 px-6">Asal Sekolah</th>
                        <th class="py-4 px-6">Tanggal Diterima</th>
                        <th class="py-4 px-6 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-6">
                            <p class="font-bold text-slate-800">Ahmad Fauzan</p>
                            <p class="text-xs text-slate-400 font-mono">0098273641</p>
                        </td>
                        <td class="py-4 px-6 font-medium">SDN 1 Wonosobo</td>
                        <td class="py-4 px-6 font-medium">28 Maret 2026</td>
                        <td class="py-4 px-6 text-right">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                Selesai Aktif
                            </span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-6">
                            <p class="font-bold text-slate-800">Siti Nurhaliza</p>
                            <p class="text-xs text-slate-400 font-mono">0098384752</p>
                        </td>
                        <td class="py-4 px-6 font-medium">SD Al-Kautsar</td>
                        <td class="py-4 px-6 font-medium">25 Maret 2026</td>
                        <td class="py-4 px-6 text-right">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">
                                Proses Berkas
                            </span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-6">
                            <p class="font-bold text-slate-800">Budi Santoso</p>
                            <p class="text-xs text-slate-400 font-mono">0099473829</p>
                        </td>
                        <td class="py-4 px-6 font-medium">MIN 2 Bandar Lampung</td>
                        <td class="py-4 px-6 font-medium">20 Maret 2026</td>
                        <td class="py-4 px-6 text-right">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                Selesai Aktif
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Sidebar Content -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Akivitas Terakhir</h3>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                <!-- Activity 1 -->
                <div class="flex gap-4">
                    <div class="relative flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 z-10 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <div class="w-px h-full bg-slate-200 absolute top-8 bottom-[-1.5rem]"></div>
                    </div>
                    <div class="pb-1">
                        <p class="text-sm font-bold text-slate-800">Data Ahmad Fauzan divalidasi</p>
                        <p class="text-xs font-medium text-slate-500 mt-0.5">Oleh Admin Sistem · 10 menit yang lalu</p>
                    </div>
                </div>
                
                <!-- Activity 2 -->
                <div class="flex gap-4">
                    <div class="relative flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 z-10 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        <div class="w-px h-full bg-slate-200 absolute top-8 bottom-[-1.5rem]"></div>
                    </div>
                    <div class="pb-1">
                        <p class="text-sm font-bold text-slate-800">Perubahan data Siti Nurhaliza</p>
                        <p class="text-xs font-medium text-slate-500 mt-0.5">Oleh Kepala TU · 2 jam yang lalu</p>
                    </div>
                </div>

                <!-- Activity 3 -->
                <div class="flex gap-4">
                    <div class="relative flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-sky-100 flex items-center justify-center text-sky-600 z-10 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                        </div>
                    </div>
                    <div class="pb-1">
                        <p class="text-sm font-bold text-slate-800">Ekspor Laporan Bulanan (PDF)</p>
                        <p class="text-xs font-medium text-slate-500 mt-0.5">Oleh Kepala Sekolah · Kemarin</p>
                    </div>
                </div>
            </div>
            
            <button class="w-full mt-6 py-2.5 px-4 bg-slate-50 hover:bg-slate-100 text-slate-700 text-sm font-bold rounded-xl transition-colors border border-slate-200">
                Lihat Seluruh Aktivitas
            </button>
        </div>
    </div>
</div>
@endsection
