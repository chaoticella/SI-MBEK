<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SI-MBEK Sistem Informasi Peternakan Kambing</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Tailwind CSS CDN -->
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
                            500: '#10b981',
                            600: '#059669',
                            800: '#014d33',
                            900: '#003824',
                            active: '#00875a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-[#014d33] via-[#013f2a] to-[#002719] min-h-screen flex items-center justify-center p-4 font-sans text-gray-800">
    <div class="max-w-md w-full">
        <!-- Logo and App Name -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-400 text-gray-900 font-extrabold text-3xl shadow-lg mb-3">
                M
            </div>
            <h1 class="text-2xl font-black text-white tracking-wider">SI-MBEK</h1>
            <p class="text-sm text-emerald-200 font-medium mt-1">Sistem Informasi Manajemen &amp; Pencatatan Peternakan Kambing</p>
            <p class="text-xs text-emerald-400/80 mt-0.5">Modul 1: Manajemen Pengguna &amp; Kandang</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 border border-white/20">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900">Masuk ke Sistem</h2>
                <p class="text-xs text-gray-500 mt-1">Silakan masukkan email dan kata sandi Anda untuk melanjutkan</p>
            </div>

            <!-- Flash Error / Success Alerts -->
            @if(session('error'))
                <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-red-500 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}" 
                               required 
                               placeholder="nama@simbek.com" 
                               class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50/50 focus:ring-red-300' : 'border-gray-200 bg-gray-50/50 focus:border-[#00875a] focus:ring-emerald-200' }} focus:outline-none focus:ring-2 transition-all">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-info text-[11px]"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               required 
                               placeholder="••••••••" 
                               class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border {{ $errors->has('password') ? 'border-red-400 bg-red-50/50 focus:ring-red-300' : 'border-gray-200 bg-gray-50/50 focus:border-[#00875a] focus:ring-emerald-200' }} focus:outline-none focus:ring-2 transition-all">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-info text-[11px]"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-gray-600">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#00875a] focus:ring-[#00875a] border-gray-300">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-3 px-4 rounded-xl bg-[#00875a] hover:bg-[#00734c] text-white font-semibold text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        <span>Masuk Sekarang</span>
                        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                    </button>
                </div>
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="mt-6 pt-5 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-500 mb-2.5 text-center">Akses Cepat Pengujian (Demo):</p>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button type="button" 
                            onclick="fillCredentials('pengelola@simbek.com', 'password123')"
                            class="py-2 px-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-xl font-medium text-center transition-colors">
                        <i class="fa-solid fa-user-shield block mb-1 text-emerald-600 text-sm"></i>
                        <span>Pengelola</span>
                    </button>
                    <button type="button" 
                            onclick="fillCredentials('pegawai@simbek.com', 'password123')"
                            class="py-2 px-2.5 bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 rounded-xl font-medium text-center transition-colors">
                        <i class="fa-solid fa-user-gear block mb-1 text-blue-600 text-sm"></i>
                        <span>Pegawai</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <p class="text-center text-xs text-emerald-300/80 mt-6">
            &copy; 2026 SI-MBEK &bull; Kelompok 8 Pemrograman Berbasis Website
        </p>
    </div>

    <script>
        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
