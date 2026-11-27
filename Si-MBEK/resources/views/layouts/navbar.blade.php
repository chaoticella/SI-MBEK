<!-- Top Navbar SI-MBEK -->
<header class="bg-white border-b border-gray-100 shadow-[0_1px_3px_rgba(0,0,0,0.02)] h-18 px-6 lg:px-8 flex items-center justify-between sticky top-0 z-20">
    <!-- Left: Page Title -->
    <div>
        <h2 class="text-xl font-bold text-gray-800 tracking-tight">
            @yield('page_title', 'Sistem Informasi Peternakan')
        </h2>
        @hasSection('page_subtitle')
            <p class="text-xs text-gray-500 mt-0.5">@yield('page_subtitle')</p>
        @endif
    </div>

    <!-- Right: Actions & User Dropdown -->
    <div class="flex items-center gap-4">
        <!-- Notification Bell (as in screenshot with red badge '3') -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" 
                    type="button" 
                    class="relative p-2.5 text-gray-600 hover:text-gray-900 rounded-full hover:bg-gray-100 focus:outline-none transition-colors">
                <i class="fa-regular fa-bell text-lg"></i>
                <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center ring-2 ring-white">
                    3
                </span>
            </button>

            <!-- Notification Dropdown Panel -->
            <div x-show="open" 
                 @click.away="open = false" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 p-4 z-50 text-sm"
                 style="display: none;">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <span class="font-bold text-gray-800">Notifikasi Peternakan</span>
                    <span class="text-xs bg-red-100 text-red-700 font-semibold px-2 py-0.5 rounded-full">3 Baru</span>
                </div>
                <div class="divide-y divide-gray-100 mt-2 space-y-2">
                    <div class="pt-2 text-xs">
                        <p class="font-medium text-amber-800">⚠️ Kambing Sakit Belum Diisolasi</p>
                        <p class="text-gray-600 mt-0.5">KB-007 (Kacang - Blok 2) terindikasi diare.</p>
                    </div>
                    <div class="pt-2 text-xs">
                        <p class="font-medium text-red-800">🔴 Pantau Kandang Isolasi</p>
                        <p class="text-gray-600 mt-0.5">KB-002 & KB-005 di Blok 3 (Isolasi) hari ke-5 & 7.</p>
                    </div>
                    <div class="pt-2 text-xs">
                        <p class="font-medium text-emerald-800">✅ Kambing Telah Sembuh</p>
                        <p class="text-gray-600 mt-0.5">KB-003 dinyatakan sembuh dari Scabies.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="h-6 w-px bg-gray-200"></div>

        <!-- User Profile Pill & Dropdown -->
        <div class="relative" x-data="{ userMenu: false }">
            <button @click="userMenu = !userMenu" 
                    type="button" 
                    class="flex items-center gap-3 pl-2 pr-3 py-1.5 rounded-full hover:bg-gray-100 transition-colors focus:outline-none">
                <div class="w-8 h-8 rounded-full bg-[#00875a] text-white font-bold flex items-center justify-center text-xs shadow-xs">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="text-left hidden sm:block">
                    <p class="text-xs font-semibold text-gray-800 leading-tight">
                        {{ auth()->user()->name ?? 'Pengguna' }}
                    </p>
                    <div class="flex items-center gap-1.5">
                        <span class="text-[11px] font-medium text-gray-500 capitalize">
                            {{ auth()->user()->role ?? 'Pegawai' }}
                        </span>
                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-down text-gray-400 text-xs ml-1"></i>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="userMenu" 
                 @click.away="userMenu = false" 
                 x-transition
                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 text-sm"
                 style="display: none;">
                <div class="px-4 py-2 border-b border-gray-100">
                    <p class="text-xs text-gray-400">Masuk sebagai</p>
                    <p class="text-xs font-bold text-gray-800 truncate">{{ auth()->user()->email ?? '-' }}</p>
                    <span class="inline-block mt-1 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md {{ auth()->user()->isPengelola() ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ auth()->user()->role_label }}
                    </span>
                </div>

                @if(auth()->user()->isPengelola())
                    <a href="{{ route('users.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fa-solid fa-users text-gray-400 w-4 text-center"></i>
                        <span>Manajemen Pengguna</span>
                    </a>
                @endif

                <a href="{{ route('kandang.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-warehouse text-gray-400 w-4 text-center"></i>
                    <span>Daftar Kandang</span>
                </a>

                <div class="border-t border-gray-100 my-1"></div>

                <!-- Form Logout -->
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs text-red-600 hover:bg-red-50 transition-colors text-left font-medium">
                        <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                        <span>Keluar / Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
