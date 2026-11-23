@extends('layouts.app')

@section('title', 'Tambah Akun Pengguna')
@section('page_title', 'Tambah Akun Pengguna Baru')
@section('page_subtitle', 'Buat kredensial akun untuk Pengelola atau Pegawai Peternakan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <div class="flex items-center justify-between pb-5 border-b border-gray-100 mb-6">
            <div>
                <h3 class="text-base font-bold text-gray-800">Formulir Pendaftaran Pengguna</h3>
                <p class="text-xs text-gray-500 mt-0.5">Tentukan nama, email, hak akses (role), dan kata sandi</p>
            </div>
            <a href="{{ route('users.index') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i>
                <span>Kembali</span>
            </a>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-regular fa-user text-xs"></i>
                    </div>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}" 
                           placeholder="Contoh: Sri Muthia Khalilah" 
                           required 
                           class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('name') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/50' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all">
                </div>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Alamat Email <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-regular fa-envelope text-xs"></i>
                    </div>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}" 
                           placeholder="nama@simbek.com" 
                           required 
                           class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/50' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all">
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role Selection -->
            <div>
                <label for="role" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Hak Akses / Peran (Role) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-shield-halved text-xs"></i>
                    </div>
                    <select name="role" 
                            id="role" 
                            required 
                            class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('role') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/50' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all text-gray-700">
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
                <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Kata Sandi <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-lock text-xs"></i>
                    </div>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           required 
                           placeholder="Minimal 6 karakter" 
                           class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('password') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/50' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all">
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
                           class="w-4 h-4 text-[#00875a] focus:ring-[#00875a] rounded border-gray-300">
                    <span class="text-xs sm:text-sm font-semibold text-gray-700">Akun Aktif (Dapat langsung digunakan untuk login)</span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('users.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs sm:text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-[#00875a] hover:bg-[#00734c] text-white text-xs sm:text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Daftarkan Akun</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
