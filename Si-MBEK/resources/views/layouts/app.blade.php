<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SI-MBEK') - Manajemen Peternakan Kambing</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind CSS (CDN for guaranteed visual fidelity and instant rendering) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#014d33',
                            900: '#003824',
                            active: '#00875a',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js for lightweight dropdowns and alerts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fbfbfa;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #c5d5cb;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #014d33;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-[#fbfbfa] text-gray-800 antialiased">
    <div class="flex flex-1 min-h-screen">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-x-hidden">
            <!-- Navbar -->
            @include('layouts.navbar')

            <!-- Main Content Area -->
            <main class="flex-1 p-6 lg:p-8 max-w-7xl w-full mx-auto">
                <!-- Flash Notification Messages -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" class="mb-5 flex items-center justify-between p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-xs">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-800 text-lg leading-none">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" class="mb-5 flex items-center justify-between p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 shadow-xs">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-exclamation text-red-600 text-lg"></i>
                            <span class="text-sm font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-red-500 hover:text-red-800 text-lg leading-none">&times;</button>
                    </div>
                @endif

                @if($errors->any())
                    <div x-data="{ show: true }" x-show="show" class="mb-5 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 shadow-xs">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-triangle-exclamation text-red-600 text-lg mt-0.5"></i>
                                <div>
                                    <h4 class="text-sm font-bold">Terdapat beberapa kesalahan pengisian form:</h4>
                                    <ul class="list-disc list-inside text-xs mt-1 space-y-0.5 text-red-700">
                                        @foreach($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button @click="show = false" class="text-red-500 hover:text-red-800 text-lg leading-none">&times;</button>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            @include('layouts.footer')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
