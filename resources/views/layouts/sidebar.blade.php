@php
    $link = fn ($name) => \Illuminate\Support\Facades\Route::has($name) ? route($name) : '#';
    $totalAlert = isset($sakitIsolasi, $sakitBelumIsolasi) ? ($sakitIsolasi->count() + $sakitBelumIsolasi->count()) : 0;
@endphp

<!-- Sidebar SI-MBEK: Forest Green Theme -->
<aside id="sidebar" 
       class="w-64 bg-[#0D4231] text-[#E7EFEA] min-h-screen flex flex-col flex-shrink-0 transition-all duration-300 border-r border-[#0A3326]">
    
    <!-- Brand Header -->
    <div class="p-5 flex items-center gap-3 border-b border-[#145641]">
        <div class="w-10 h-10 rounded-xl bg-amber-400 text-[#0D4231] font-black flex items-center justify-center text-xl shadow-xs">
            M
        </div>
        <div>
            <h1 class="text-base font-bold tracking-wide text-white leading-tight">SI-MBEK</h1>
            <p class="text-[11px] text-[#A3CFBE] font-medium leading-none mt-0.5">Manajemen Peternakan</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 px-3 py-4 space-y-5 overflow-y-auto">
        <!-- Main Navigation -->
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ $link('dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-[#185A43] text-white shadow-xs font-semibold' : 'text-[#D0E2D8] hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-gauge w-5 text-center text-[#84A98C]"></i>
                <span>Dashboard</span>
            </a>

            <!-- Data Kandang (Modul 1 Utama) -->
            <a href="{{ $link('kandang.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('kandang.*') ? 'bg-[#185A43] text-white shadow-xs font-semibold' : 'text-[#D0E2D8] hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-warehouse w-5 text-center text-[#84A98C]"></i>
                <span>Data Kandang</span>
            </a>

            <!-- Data Kambing (Modul 2) -->
            <a href="{{ $link('kambing.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('kambing.*') ? 'bg-[#185A43] text-white shadow-xs font-semibold' : 'text-[#D0E2D8] hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-paw w-5 text-center text-[#84A98C]"></i>
                <span>Data Kambing</span>
            </a>

            <!-- Perkawinan -->
            <a href="{{ $link('perkawinan.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('perkawinan.*') ? 'bg-[#185A43] text-white shadow-xs font-semibold' : 'text-[#D0E2D8] hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-heart-pulse w-5 text-center text-[#84A98C]"></i>
                <span>Perkawinan</span>
            </a>
        </div>

        <!-- Section: KESEHATAN -->
        <div>
            <div class="px-3 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-[#84A98C]">
                Kesehatan
            </div>
            <div class="space-y-1">
                <!-- Kondisi Fisik -->
                <a href="{{ $link('kondisi-fisik.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('kondisi-fisik.*') ? 'bg-[#185A43] text-white shadow-xs font-semibold' : 'text-[#D0E2D8] hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-notes-medical w-5 text-center text-[#84A98C]"></i>
                    <span>Kondisi Fisik</span>
                </a>

                <!-- Pencatatan Penyakit (dengan alert badge jika ada kambing sakit) -->
                <a href="{{ $link('penyakit.index') }}" 
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('penyakit.*') ? 'bg-[#185A43] text-white shadow-xs font-semibold' : 'text-[#D0E2D8] hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-virus-covid w-5 text-center text-[#84A98C]"></i>
                        <span>Pencatatan Penyakit</span>
                    </div>
                    @if($totalAlert > 0)
                        <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-xs">
                            {{ $totalAlert }}
                        </span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Section: PENGELOLA (Khusus Pengelola) -->
        @if(auth()->check() && (auth()->user()->isPengelola() || auth()->user()->role === 'pemilik'))
        <div>
            <div class="px-3 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-[#84A98C]">
                Akses Pengelola
            </div>
            <div class="space-y-1">
                <a href="{{ $link('users.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('users.*') ? 'bg-[#185A43] text-white shadow-xs font-semibold' : 'text-[#D0E2D8] hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center text-[#84A98C]"></i>
                    <span>Manajemen Pengguna</span>
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- User Account Summary at Bottom of Sidebar -->
    @if(auth()->check())
    <div class="p-3.5 border-t border-[#145641] bg-[#0A3527]/70">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-full bg-[#185A43] border border-[#2D7A5E] flex items-center justify-center font-bold text-white text-xs">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="inline-block w-1.5 h-1.5 rounded-full {{ auth()->user()->status_aktif ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                    <span class="text-[11px] text-[#A3CFBE] capitalize font-medium">{{ auth()->user()->role_label }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif
</aside>
