<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - Buku Induk SD Muhammadiyah Gisting</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    @endif
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar for modern look */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-hidden">
    
    <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="sidebarOpen = window.innerWidth >= 1024" class="flex h-screen w-full">

        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-slate-900/60 lg:hidden backdrop-blur-sm" @click="sidebarOpen = false" x-cloak></div>

        <!-- Sidebar Navigation (With custom blue/yellow theme) -->
        <aside :class="sidebarOpen ? 'translate-x-0 lg:ml-0' : '-translate-x-full lg:-ml-64'" class="fixed inset-y-0 left-0 z-30 w-64 bg-[#0c4a6e] text-slate-300 transition-all duration-300 lg:static flex flex-col h-full shadow-2xl shrink-0">
            
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-6 bg-[#08334d] shrink-0 border-b border-sky-900/50">
                <a href="{{ url('/') }}" class="flex items-center gap-3 font-bold text-[1.15rem] tracking-tight text-white">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-yellow-400 to-yellow-500 flex items-center justify-center text-[#08334d] text-xs shadow-md shadow-yellow-500/20">BI</div>
                    Buku Induk<span class="text-yellow-400">.</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-sky-200 hover:text-white focus:outline-none cursor-pointer p-1.5 rounded-lg hover:bg-sky-800 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Sidebar Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all border group
                          {{ request()->routeIs('dashboard') 
                             ? 'bg-sky-800/80 text-white font-semibold border-sky-700/50 shadow-inner' 
                             : 'text-sky-100 hover:bg-sky-800/50 hover:text-white font-medium border-transparent' }}">
                    <svg class="w-5 h-5 transition-colors {{ request()->routeIs('dashboard') ? 'text-yellow-400' : 'opacity-70 group-hover:text-yellow-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <div x-data="{ open: {{ (request()->routeIs('siswas.*') || request()->routeIs('rombels.*')) ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2.5 rounded-xl border group transition-all
                                   {{ (request()->routeIs('siswas.*') || request()->routeIs('rombels.*')) 
                                      ? 'bg-sky-800/80 text-white font-semibold border-sky-700/50 shadow-inner' 
                                      : 'text-sky-100 hover:bg-sky-800/50 hover:text-white font-medium border-transparent' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 transition-colors {{ (request()->routeIs('siswas.*') || request()->routeIs('rombels.*')) ? 'text-yellow-400' : 'opacity-70 group-hover:text-yellow-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Data Pokok Siswa
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 -translate-y-2" 
                         x-transition:enter-end="opacity-100 translate-y-0" 
                         class="mt-1 ml-4 pl-4 border-l border-sky-800/50 space-y-1" x-cloak>
                        <a href="{{ route('siswas.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('siswas.*') ? 'text-yellow-400 font-bold bg-sky-800/40' : 'text-sky-200 hover:text-white hover:bg-sky-800/30' }}">
                            • Daftar Siswa
                        </a>
                        <a href="{{ route('rombels.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('rombels.*') ? 'text-yellow-400 font-bold bg-sky-800/40' : 'text-sky-200 hover:text-white hover:bg-sky-800/30' }}">
                            • Rombongan Belajar
                        </a>
                    </div>
                </div>

                {{-- Buku Induk — menu mandiri di bawah Data Pokok Siswa --}}
                <a href="{{ route('buku-induk.index') }}"
                <a href="{{ route('buku-induk.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all border group
                          {{ request()->routeIs('buku-induk.*')
                             ? 'bg-sky-800/80 text-white font-semibold border-sky-700/50 shadow-inner'
                             : 'text-sky-100 hover:bg-sky-800/50 hover:text-white font-medium border-transparent' }}">
                    <svg class="w-5 h-5 transition-colors {{ request()->routeIs('buku-induk.*') ? 'text-yellow-400' : 'opacity-70 group-hover:text-yellow-400' }}"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Buku Induk
                    @if(request()->routeIs('buku-induk.*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-yellow-400"></span>
                    @endif
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sky-100 hover:bg-sky-800/50 hover:text-white font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Eksport & Laporan
                </a>
                
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sky-100 hover:bg-sky-800/50 hover:text-white font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Riwayat Status
                </a>

                @hasrole('Super Admin')
                <div class="pt-5 mt-5 border-t border-sky-800">
                    <p class="px-3 text-[0.7rem] font-bold text-sky-400 uppercase tracking-widest mb-2">Administrator</p>
                    <a href="{{ route('users.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all border group
                              {{ request()->routeIs('users.*') 
                                 ? 'bg-sky-800/80 text-white font-semibold border-sky-700/50 shadow-inner' 
                                 : 'text-sky-100 hover:bg-sky-800/50 hover:text-white font-medium border-transparent' }}">
                        <svg class="w-5 h-5 transition-colors {{ request()->routeIs('users.*') ? 'text-yellow-400' : 'opacity-70 group-hover:text-yellow-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Manajemen User
                    </a>
                    <a href="{{ route('roles.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all border group
                              {{ request()->routeIs('roles.*') 
                                 ? 'bg-sky-800/80 text-white font-semibold border-sky-700/50 shadow-inner' 
                                 : 'text-sky-100 hover:bg-sky-800/50 hover:text-white font-medium border-transparent' }}">
                        <svg class="w-5 h-5 transition-colors {{ request()->routeIs('roles.*') ? 'text-yellow-400' : 'opacity-70 group-hover:text-yellow-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Manajemen Role
                    </a>
                    @hasanyrole('Super Admin|Operator')
                    <a href="{{ route('tahun-pelajaran.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all border group
                              {{ request()->routeIs('tahun-pelajaran.*') 
                                 ? 'bg-sky-800/80 text-white font-semibold border-sky-700/50 shadow-inner' 
                                 : 'text-sky-100 hover:bg-sky-800/50 hover:text-white font-medium border-transparent' }}">
                        <svg class="w-5 h-5 transition-colors {{ request()->routeIs('tahun-pelajaran.*') ? 'text-yellow-400' : 'opacity-70 group-hover:text-yellow-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Tahun Pelajaran
                    </a>
                    @endhasanyrole
                </div>
                @endhasrole

                <div class="pt-5 mt-5 border-t border-sky-800">
                    <p class="px-3 text-[0.7rem] font-bold text-sky-400 uppercase tracking-widest mb-2">Pengaturan</p>
                    <a href="{{ route('mata-pelajaran.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all border group
                              {{ request()->routeIs('mata-pelajaran.*') 
                                 ? 'bg-sky-800/80 text-white font-semibold border-sky-700/50 shadow-inner' 
                                 : 'text-sky-100 hover:bg-sky-800/50 hover:text-white font-medium border-transparent' }}">
                        <svg class="w-5 h-5 transition-colors {{ request()->routeIs('mata-pelajaran.*') ? 'text-yellow-400' : 'opacity-70 group-hover:text-yellow-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        Konfigurasi Mapel
                    </a>
                    <a href="{{ route('settings.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all border group
                              {{ request()->routeIs('settings.*') 
                                 ? 'bg-sky-800/80 text-white font-semibold border-sky-700/50 shadow-inner' 
                                 : 'text-sky-100 hover:bg-sky-800/50 hover:text-white font-medium border-transparent' }}">
                        <svg class="w-5 h-5 transition-colors {{ request()->routeIs('settings.*') ? 'text-yellow-400' : 'opacity-70 group-hover:text-yellow-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Konfigurasi Sistem
                    </a>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-sky-900 shrink-0 bg-[#08334d]">
                <form action="{{ route('logout') }}" method="POST" class="w-full m-0 p-0">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-rose-300 hover:bg-rose-500 hover:text-white font-semibold transition-colors w-full border border-rose-900/50 cursor-pointer">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout Sesi
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex flex-1 flex-col overflow-hidden bg-slate-50 relative">
            
            <!-- Top Header -->
            <header class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8 bg-white/70 backdrop-blur-md border-b border-slate-200/60 sticky top-0 z-10 w-full shrink-0 shadow-sm">
                <!-- Hamburger Menu for both Desktop and Mobile -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-[#0ea5e9] bg-white border border-slate-200 hover:border-sky-200 p-2 rounded-xl transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 cursor-pointer">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                    </button>
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                        @yield('header_title', 'Dashboard')
                    </h1>

                    @php
                        $tpAktif = \App\Models\TahunPelajaran::where('is_aktif', true)->first();
                    @endphp
                    @if($tpAktif)
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-sky-50 border border-sky-100 rounded-xl">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-500"></span>
                        </span>
                        <p class="text-[0.7rem] font-black text-sky-700 uppercase tracking-tighter">
                            Sesi Aktif: {{ $tpAktif->tahun }} - {{ $tpAktif->semester }}
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Header Actions (Right) -->
                <div class="flex items-center gap-3 sm:gap-5">
                    <!-- Notifications -->
                    <button class="relative p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100 transition-colors hidden sm:block cursor-pointer">
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></span>
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </button>

                    <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

                    <!-- Profile Dropdown Menu -->
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <!-- Profile Trigger Button -->
                        <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="cursor-pointer flex items-center gap-2.5 rounded-full p-1 pl-1.5 border hover:bg-slate-50 transition-all focus:outline-none focus:ring-2 focus:ring-sky-500/20 bg-white shadow-sm">
                            <img class="w-8 h-8 rounded-full object-cover ring-2 ring-white" src="https://ui-avatars.com/api/?name=Admin+Sistem&background=0284c7&color=fff&bold=true" alt="UserAvatar">
                            <span class="hidden sm:block text-sm font-semibold text-slate-700 pr-1">Admin TU</span>
                            <svg class="hidden sm:block w-4 h-4 text-slate-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <!-- Dropdown Panel -->
                        <div x-show="dropdownOpen" 
                             x-transition:enter="transition ease-out duration-150" 
                             x-transition:enter-start="transform opacity-0 scale-95 translate-y-2" 
                             x-transition:enter-end="transform opacity-100 scale-100 translate-y-0" 
                             x-transition:leave="transition ease-in duration-100" 
                             x-transition:leave-start="transform opacity-100 scale-100" 
                             x-transition:leave-end="transform opacity-0 scale-95" 
                             class="absolute right-0 mt-3 w-60 bg-white border border-slate-100 rounded-2xl shadow-xl shadow-slate-200/50 py-2 z-50 text-sm" x-cloak>
                            
                            <div class="px-4 py-3 border-b border-slate-100 mb-1">
                                <p class="font-bold text-slate-800 text-base">Kepala Tata Usaha</p>
                                <p class="text-slate-500 text-xs mt-0.5 truncate font-medium">admin@sdmuhgisting.sch.id</p>
                            </div>
                            <a href="#" class="flex items-center gap-2 px-4 py-2.5 text-slate-600 hover:bg-sky-50 hover:text-sky-700 font-medium transition-colors">
                                <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> Profil Pengguna
                            </a>
                            <a href="#" class="flex items-center gap-2 px-4 py-2.5 text-slate-600 hover:bg-sky-50 hover:text-sky-700 font-medium transition-colors">
                                <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Pengaturan
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form action="{{ route('logout') }}" method="POST" class="m-0 p-0 w-full block">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 px-4 py-2.5 text-rose-600 hover:bg-rose-50 hover:text-rose-700 font-medium transition-colors w-full text-left cursor-pointer">
                                    <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg> Keluar Aplikasi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Breadcrumbs -->
            <div class="px-4 sm:px-6 lg:px-8 py-3 bg-white border-b border-slate-200 shadow-sm sticky top-16 z-0">
                <nav class="flex text-sm font-medium text-slate-500 whitespace-nowrap overflow-x-auto custom-scrollbar-hide">
                    <ol class="flex items-center space-x-2">
                        <li>
                            <a href="{{ url('/dashboard') }}" class="hover:text-[#0ea5e9] transition-colors flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Beranda
                            </a>
                        </li>
                        @hasSection('breadcrumb')
                            <li><span class="text-slate-300 mx-1">/</span></li>
                            <li class="text-slate-800 font-semibold flex items-center">
                                @yield('breadcrumb')
                            </li>
                        @endif
                    </ol>
                </nav>
            </div>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto w-full p-4 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto space-y-6 lg:space-y-8 min-h-[70vh]">
                    @yield('content')
                </div>
                
                <footer class="py-6 max-w-7xl mx-auto w-full text-center sm:text-left border-t border-slate-200/50 mt-12 mb-4">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">
                        &copy; {{ date('Y') }} SISTEM INFO BUKU INDUK · SD Muhammadiyah Gisting.
                    </p>
                </footer>
            </main>
            
        </div>
    </div>
</body>
</html>
