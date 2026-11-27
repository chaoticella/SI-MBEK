<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SI-MBEK') &bull; Manajemen Peternakan Kambing</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Tailwind CSS CDN configured with Warm Cream & Forest Green Characteristics -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        cream: {
                            50: '#FDFBF7',
                            100: '#FAF7F2',
                            200: '#F3EDE2',
                            300: '#EAE1D2',
                        },
                        forest: {
                            700: '#165B43',
                            800: '#0F513C',
                            900: '#0D4231',
                            950: '#072B1F',
                        },
                        sage: {
                            400: '#84A98C',
                            500: '#6B9080',
                            600: '#52796F',
                        },
                        earth: {
                            400: '#B08968',
                            500: '#A3704C',
                            600: '#8D5B4C',
                            700: '#6F4E37',
                        }
                    }
                }
            }
        }
    </script>

    <!-- jQuery for interactive health module features -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FAF7F2;
            color: #2D2A26;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #FAF7F2;
        }
        ::-webkit-scrollbar-thumb {
            background: #D9D2C5;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #0D4231;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-[#FAF7F2] text-stone-800 antialiased selection:bg-[#6B9080] selection:text-white">
    <div class="flex flex-1 min-h-screen">
        <!-- Sidebar: Forest Green -->
        @include('layouts.sidebar')

        <!-- Mobile Sidebar Backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-black/40 lg:hidden"></div>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-x-hidden">
            <!-- Navbar: Profil & Notifikasi saja (tanpa judul halaman) -->
            @include('layouts.navbar')

            <!-- Main Content Area: Background Warm Cream -->
            <main class="flex-1 p-4 lg:p-8 max-w-7xl w-full mx-auto space-y-5">
                
                {{-- Banner Kandang Isolasi (Perhatian Khusus Ternak Sakit) --}}
                @if(isset($sakitIsolasi) && $sakitIsolasi->isNotEmpty())
                    <div class="js-dismissable flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-xs sm:text-sm text-red-900 shadow-2xs">
                        <span class="relative mt-0.5 flex h-3 w-3 shrink-0">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex h-3 w-3 rounded-full bg-red-600"></span>
                        </span>
                        <div class="flex-1">
                            <p class="font-bold">{{ $sakitIsolasi->count() }} kambing sakit di kandang isolasi perlu dipantau</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach ($sakitIsolasi as $k)
                                    <a href="{{ route('penyakit.show', $k->id_penyakit ?? $k->id) }}"
                                       class="rounded-full border border-red-300 bg-white px-3 py-0.5 text-xs font-semibold hover:bg-red-100 transition-colors">
                                        {{ $k->kambing?->kode }} &bull; {{ $k->kambing?->kandang?->label ?? 'Isolasi' }} &bull; hari ke-{{ ($k->lama_sakit ?? 0) + 1 }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <button type="button" class="js-dismiss text-red-400 hover:text-red-700 text-lg leading-none" aria-label="Tutup">&times;</button>
                    </div>
                @endif

                @if(isset($sakitBelumIsolasi) && $sakitBelumIsolasi->isNotEmpty())
                    <div class="js-dismissable flex items-center justify-between rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs sm:text-sm text-amber-900 shadow-2xs">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-triangle-exclamation text-amber-500 text-base"></i>
                            <div>
                                <span class="font-semibold">{{ $sakitBelumIsolasi->count() }} kambing sakit belum di kandang isolasi:</span>
                                <span class="font-bold text-amber-950 ml-1">
                                    {{ $sakitBelumIsolasi->map(fn ($k) => $k->kambing?->kode)->filter()->join(', ') }}
                                </span>
                            </div>
                        </div>
                        <button type="button" class="js-dismiss text-amber-400 hover:text-amber-700 text-lg leading-none" aria-label="Tutup">&times;</button>
                    </div>
                @endif

                {{-- Flash Message Notifikasi --}}
                @if(session('success'))
                    <div class="js-dismissable flex items-center justify-between p-4 rounded-2xl bg-[#E8F3EE] border border-[#C5E2D4] text-[#0D4231] text-xs sm:text-sm shadow-2xs">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-check text-[#165B43] text-base"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button type="button" class="js-dismiss text-[#165B43] hover:text-[#0D4231] text-lg leading-none">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="js-dismissable flex items-center justify-between p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm shadow-2xs">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-exclamation text-red-600 text-base"></i>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button type="button" class="js-dismiss text-red-500 hover:text-red-800 text-lg leading-none">&times;</button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="js-dismissable p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm shadow-2xs">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-triangle-exclamation text-red-600 text-base mt-0.5"></i>
                                <div>
                                    <h4 class="font-bold text-red-900">Periksa kembali isian formulir Anda:</h4>
                                    <ul class="list-disc list-inside mt-1 space-y-0.5 text-xs text-red-700">
                                        @foreach($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="js-dismiss text-red-400 hover:text-red-800 text-lg leading-none">&times;</button>
                        </div>
                    </div>
                @endif

                <!-- Yield Content -->
                @yield('content')
            </main>

            <!-- Footer -->
            @include('layouts.footer')
        </div>
    </div>

    <script>
        // Dismissible banners
        document.querySelectorAll('.js-dismiss').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.closest('.js-dismissable')?.remove();
            });
        });

        // Mobile sidebar toggle
        const btnSidebar = document.getElementById('btn-sidebar');
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');

        if (btnSidebar && sidebar && backdrop) {
            btnSidebar.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                backdrop.classList.toggle('hidden');
            });

            backdrop.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            });
        }
    </script>

    <!-- Health module interactive script -->
    <script src="/resources/js/kesehatan.js"></script>

    @stack('scripts')
</body>
</html>
