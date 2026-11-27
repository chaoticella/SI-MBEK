@extends('layouts.app')

@section('title', 'Tambah Akun Pengguna')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">
    <!-- Header Halaman Langsung di Konten -->
    <div class="flex items-center justify-between pb-1">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xl">👥</span>
                <h1 class="text-xl font-black text-stone-900 tracking-tight">Tambah Akun Pengguna Baru</h1>
            </div>
            <p class="text-xs text-stone-500 mt-0.5">Buat kredensial akun untuk Pengelola atau Pegawai Peternakan</p>
        </div>

        <a href="{{ route('users.index') }}" 
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-[#ECE5DA] bg-white text-xs font-semibold text-stone-600 hover:bg-[#FAF7F2] transition-colors shadow-2xs">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Card Putih dengan Rounded Corners -->
    <div class="bg-white rounded-3xl border border-[#ECE5DA] shadow-2xs p-6 sm:p-8">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                        <i class="fa-regular fa-user text-xs"></i>
                    </div>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}" 
                           placeholder="Contoh: Sri Muthia Khalilah" 
                           required 
                           class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('name') ? 'border-red-400 bg-red-50/40' : 'border-[#ECE5DA] bg-[#FAF7F2]/60' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all">
                </div>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                    Alamat Email <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                        <i class="fa-regular fa-envelope text-xs"></i>
                    </div>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}" 
                           placeholder="nama@simbek.com" 
                           required 
                           class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50/40' : 'border-[#ECE5DA] bg-[#FAF7F2]/60' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all">
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role Selection -->
            <div>
                <label for="role" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                    Hak Akses / Peran (Role) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                        <i class="fa-solid fa-shield-halved text-xs"></i>
                    </div>
                    <select name="role" 
                            id="role" 
                            required 
                            class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('role') ? 'border-red-400 bg-red-50/40' : 'border-[#ECE5DA] bg-[#FAF7F2]/60' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all text-stone-700">
                        <option value="" disabled selected>-- Pilih Peran Pengguna --</option>
                        <option value="pengelola" {{ old('role') === 'pengelola' ? 'selected' : '' }}>Pengelola (Akses Penuh: Kandang &amp; Akun)</option>
                        <option value="pegawai" {{ old('role') === 'pegawai' ? 'selected' : '' }}>Pegawai (Akses Operasional: Kandang, Kambing, Medis)</option>
                    </select>
                </div>
                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                    Kata Sandi <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                        <i class="fa-solid fa-lock text-xs"></i>
                    </div>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           required 
                           placeholder="Minimal 6 karakter" 
                           class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('password') ? 'border-red-400 bg-red-50/40' : 'border-[#ECE5DA] bg-[#FAF7F2]/60' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all">
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif -->
            <div class="pt-1">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" 
                           name="status_aktif" 
                           value="1" 
                           {{ old('status_aktif', '1') ? 'checked' : '' }} 
                           class="w-4 h-4 text-[#0D4231] focus:ring-[#0D4231] rounded border-[#ECE5DA]">
                    <span class="text-xs sm:text-sm font-semibold text-stone-700">Akun Aktif (Dapat langsung digunakan untuk login)</span>
                </label>
            </div>

            <!-- Action Buttons: Forest Green Button -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#ECE5DA]">
                <a href="{{ route('users.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-[#ECE5DA] text-xs sm:text-sm font-semibold text-stone-600 hover:bg-[#FAF7F2] transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-[#0D4231] hover:bg-[#082E22] text-white text-xs sm:text-sm font-bold shadow-xs transition-all flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Daftarkan Akun</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
