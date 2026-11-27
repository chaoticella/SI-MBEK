@php
    $totalAlert = isset($sakitIsolasi, $sakitBelumIsolasi) ? ($sakitIsolasi->count() + $sakitBelumIsolasi->count()) : 0;
@endphp

<!-- Top Navbar SI-MBEK: Bersih & Minimalis (Hanya Profil & Notifikasi) -->
<header class="bg-white/95 backdrop-blur border-b border-[#ECE5DA] h-16 px-4 lg:px-8 flex items-center justify-between sticky top-0 z-20 shadow-[0_1px_4px_rgba(0,0,0,0.02)]">
    <!-- Left: Mobile Toggle & Farm Branding -->
    <div class="flex items-center gap-3">
        <button id="btn-sidebar" type="button" class="rounded-xl p-2 text-stone-600 hover:bg-[#F5F0E6] lg:hidden focus:outline-none transition-colors" aria-label="Buka Menu">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <!-- Right: Notifikasi & Profil Pengguna -->
    <div class="flex items-center gap-3 sm:gap-4">
        <!-- Notifikasi Pemantauan Ternak (Lonceng) -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                    type="button"
                    class="relative p-2 text-stone-600 hover:text-stone-900 rounded-xl hover:bg-[#F5F0E6] focus:outline-none transition-colors"
                    aria-label="Notifikasi Kesehatan Ternak">
                <i class="fa-regular fa-bell text-base"></i>
                @if($totalAlert > 0)
                    <span class="absolute top-1 right-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold text-white shadow-xs">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-60"></span>
                        <span class="relative">{{ $totalAlert }}</span>
                    </span>
                @endif
            </button>

            <!-- Dropdown Notifikasi -->
            <div x-show="open"
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-[#ECE5DA] overflow-hidden z-50 text-xs"
                 style="display: none;">
                <div class="flex items-center justify-between p-3.5 bg-[#FAF7F2] border-b border-[#ECE5DA]">
                    <span class="font-bold text-stone-800">Pantauan Kesehatan Kambing</span>
                    @if($totalAlert > 0)
                        <span class="bg-red-100 text-red-700 font-bold px-2 py-0.5 rounded-full text-[10px]">{{ $totalAlert }} Perhatian</span>
                    @endif
                </div>

                <div class="divide-y divide-[#ECE5DA] max-h-72 overflow-y-auto">
                    @if(isset($sakitIsolasi) && $sakitIsolasi->isNotEmpty())
                        @foreach($sakitIsolasi as $kasus)
                            <a href="{{ route('penyakit.show', $kasus->id_penyakit ?? $kasus->id) }}" class="flex items-start gap-2.5 p-3 hover:bg-[#FAF7F2] transition-colors block">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500 mt-1 flex-shrink-0 animate-pulse"></span>
                                <div>
                                    <p class="font-bold text-stone-800">{{ $kasus->kambing?->kode ?? 'Kambing' }} &bull; {{ $kasus->diagnosa ?: 'Sakit' }}</p>
                                    <p class="text-[11px] text-stone-500 mt-0.5">Di kandang isolasi &bull; hari ke-{{ ($kasus->lama_sakit ?? 0) + 1 }}</p>
                                </div>
                            </a>
                        @endforeach
                    @endif

                    @if(isset($sakitBelumIsolasi) && $sakitBelumIsolasi->isNotEmpty())
                        @foreach($sakitBelumIsolasi as $kasus)
                            <a href="{{ route('penyakit.show', $kasus->id_penyakit ?? $kasus->id) }}" class="flex items-start gap-2.5 p-3 hover:bg-[#FAF7F2] transition-colors block">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <p class="font-bold text-amber-900">{{ $kasus->kambing?->kode ?? 'Kambing' }} &bull; Belum Diisolasi!</p>
                                    <p class="text-[11px] text-stone-500 mt-0.5">{{ $kasus->diagnosa ?: $kasus->gejala }}</p>
                                </div>
                            </a>
                        @endforeach
                    @endif

                    @if($totalAlert === 0)
                        <div class="p-6 text-center text-stone-400">
                            <span class="text-2xl block mb-1">🐐</span>
                            <p class="font-medium text-stone-600">Semua kambing terpantau sehat</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="h-5 w-px bg-[#ECE5DA]"></div>

        <!-- Profil Pemilik / Pegawai -->
        @if(auth()->check())
        <div class="relative" x-data="{ userMenu: false }">
            <button @click="userMenu = !userMenu"
                    type="button"
                    class="flex items-center gap-2.5 pl-1.5 pr-2.5 py-1.5 rounded-full hover:bg-[#FAF7F2] border border-transparent hover:border-[#ECE5DA] transition-all focus:outline-none">
                <!-- Avatar -->
                <div class="w-8 h-8 rounded-full bg-[#0D4231] text-white font-bold flex items-center justify-center text-xs shadow-2xs">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>

                <!-- Info Profil -->
                <div class="text-left hidden sm:block">
                    <p class="text-xs font-bold text-stone-800 leading-tight">
                        {{ auth()->user()->name }}
                    </p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.2 rounded {{ auth()->user()->isPengelola() ? 'bg-amber-100 text-amber-800' : 'bg-[#EAE4D9] text-stone-700' }}">
                            {{ auth()->user()->role_label }}
                        </span>
                    </div>
                </div>

                <i class="fa-solid fa-chevron-down text-stone-400 text-[10px] ml-0.5"></i>
            </button>

            <!-- Dropdown Menu Profil -->
            <div x-show="userMenu"
                 @click.away="userMenu = false"
                 x-transition
                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-[#ECE5DA] py-2 z-50 text-xs"
                 style="display: none;">

                <!-- Form Logout -->
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-red-600 hover:bg-red-50 transition-colors text-left font-semibold">
                        <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                        <span>Keluar / Logout</span>
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</header>
