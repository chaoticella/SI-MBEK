<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk &bull; SI-MBEK Sistem Informasi Peternakan Kambing</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FAF7F2] min-h-screen flex items-center justify-center p-4 font-sans text-stone-800 selection:bg-[#6B9080] selection:text-white">
    <div class="max-w-md w-full">
        <!-- Logo & Branding -->
        <div class="text-center mb-7">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-400 text-[#0D4231] font-black text-3xl shadow-sm mb-3">
                M
            </div>
            <div class="flex items-center justify-center gap-2">
                <span class="text-xl">🐐</span>
                <h1 class="text-2xl font-black text-stone-900 tracking-wider">SI-MBEK</h1>
            </div>
            <p class="text-xs text-stone-500 font-medium mt-1">Sistem Informasi Manajemen &amp; Pencatatan Peternakan Kambing</p>
            <p class="text-[11px] text-[#0D4231] font-bold mt-0.5">Modul 1: Manajemen Pengguna &amp; Kandang</p>
        </div>

        <!-- Login Card: Putih dengan Rounded Corners -->
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(43,41,39,0.06)] p-8 border border-[#ECE5DA]">
            <div class="mb-6">
                <h2 class="text-xl font-black text-stone-900">Masuk ke Sistem</h2>
                <p class="text-xs text-stone-500 mt-1">Silakan masukkan email dan kata sandi Anda</p>
            </div>

            <!-- Flash Error / Success Alerts -->
            @if(session('error'))
                <div class="mb-5 p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-red-500 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 p-3.5 rounded-2xl bg-[#E8F3EE] border border-[#C5E2D4] text-[#0D4231] text-xs flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-[#165B43] text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}" 
                               required 
                               placeholder="nama@simbek.com" 
                               class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50/50' : 'border-[#ECE5DA] bg-[#FAF7F2]/60' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-info text-[11px]"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               required 
                               placeholder="••••••••" 
                               class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('password') ? 'border-red-400 bg-red-50/50' : 'border-[#ECE5DA] bg-[#FAF7F2]/60' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-info text-[11px]"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-stone-600">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#0D4231] focus:ring-[#0D4231] border-[#ECE5DA]">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Buttons: Forest Green Button -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-3 px-4 rounded-xl bg-[#0D4231] hover:bg-[#082E22] text-white font-bold text-xs sm:text-sm shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-2">
                        <span>Masuk Sekarang</span>
                        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                    </button>
                </div>
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="mt-6 pt-5 border-t border-[#ECE5DA]">
                <p class="text-xs font-semibold text-stone-400 mb-2.5 text-center">Akses Cepat Pengujian (Demo):</p>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button type="button" 
                            onclick="fillCredentials('pengelola@simbek.com', 'password123')"
                            class="py-2.5 px-3 bg-[#FAF7F2] hover:bg-[#F3EDE2] text-stone-800 border border-[#ECE5DA] rounded-xl font-semibold text-center transition-colors">
                        <i class="fa-solid fa-user-shield block mb-1 text-[#A3704C] text-sm"></i>
                        <span>Pengelola</span>
                    </button>
                    <button type="button" 
                            onclick="fillCredentials('pegawai@simbek.com', 'password123')"
                            class="py-2.5 px-3 bg-[#FAF7F2] hover:bg-[#F3EDE2] text-stone-800 border border-[#ECE5DA] rounded-xl font-semibold text-center transition-colors">
                        <i class="fa-solid fa-user-gear block mb-1 text-[#6B9080] text-sm"></i>
                        <span>Pegawai</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <p class="text-center text-xs text-stone-400 mt-6">
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
