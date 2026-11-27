<!-- Sidebar SI-MBEK -->
<aside class="w-64 bg-[#014d33] text-white min-h-screen flex flex-col flex-shrink-0 transition-all duration-300">
    <!-- Brand Header -->
    <div class="p-5 flex items-center gap-3 border-b border-[#046243]">
        <div class="w-10 h-10 rounded-xl bg-amber-400 text-gray-900 font-extrabold flex items-center justify-center text-xl shadow-sm">
            M
        </div>
        <div>
            <h1 class="text-lg font-bold tracking-wide text-white leading-tight">SI-MBEK</h1>
            <p class="text-xs text-emerald-200/80 font-medium leading-none mt-0.5">Manajemen Peternakan</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 px-4 py-5 space-y-6 overflow-y-auto">
        <!-- Main Navigation -->
        <div class="space-y-1.5">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-[#00875a] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-gauge w-5 text-center text-emerald-300"></i>
                <span>Dashboard</span>
            </a>

            <!-- Data Kandang (Modul 1 Utama) -->
            <a href="{{ route('kandang.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('kandang.*') ? 'bg-[#00875a] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-warehouse w-5 text-center text-emerald-300"></i>
                <span>Data Kandang</span>
            </a>

            <!-- Data Kambing (Modul 2) -->
            <a href="{{ route('kambing.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('kambing.*') ? 'bg-[#00875a] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-paw w-5 text-center text-emerald-300"></i>
                <span>Data Kambing</span>
            </a>

            <!-- Perkawinan (Modul 3) -->
            <a href="{{ route('perkawinan.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('perkawinan.*') ? 'bg-[#00875a] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-heart-pulse w-5 text-center text-emerald-300"></i>
                <span>Perkawinan</span>
            </a>
        </div>

        <!-- Section: KESEHATAN -->
        <div>
            <div class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-emerald-300/80">
                Kesehatan
            </div>
            <div class="space-y-1.5">
                <!-- Kondisi Fisik -->
                <a href="{{ route('kondisi-fisik.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('kondisi-fisik.*') ? 'bg-[#00875a] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-notes-medical w-5 text-center text-emerald-300"></i>
                    <span>Kondisi Fisik</span>
                </a>

                <!-- Pencatatan Penyakit (with badge '3' like screenshot) -->
                <a href="{{ route('penyakit.index') }}" 
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('penyakit.*') ? 'bg-[#00875a] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-virus-covid w-5 text-center text-emerald-300"></i>
                        <span>Pencatatan Penyakit</span>
                    </div>
                    <span class="bg-red-500 text-white text-[11px] font-bold px-2 py-0.5 rounded-full shadow-xs">
                        3
                    </span>
                </a>
            </div>
        </div>

        <!-- Section: PENGELOLA / ROLE MANAGEMENT (Khusus Pengelola) -->
        @if(auth()->check() && auth()->user()->isPengelola())
        <div>
            <div class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-emerald-300/80">
                Akses Pengelola
            </div>
            <div class="space-y-1.5">
                <!-- Kelola Pengguna (Modul 1 Role Management) -->
                <a href="{{ route('users.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('users.*') ? 'bg-[#00875a] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center text-emerald-300"></i>
                    <span>Manajemen Pengguna</span>
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- User Account Brief / Logout Footer -->
    <div class="p-4 border-t border-[#046243] bg-[#003d28]/70">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-emerald-700/80 border border-emerald-400/40 flex items-center justify-center font-bold text-white text-sm">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="inline-block w-2 h-2 rounded-full {{ auth()->user()->status_aktif ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                    <span class="text-xs text-emerald-200 capitalize font-medium">{{ auth()->user()->role ?? 'Pegawai' }}</span>
                </div>
            </div>
        </div>
    </div>
</aside>
