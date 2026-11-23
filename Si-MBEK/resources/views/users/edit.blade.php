@extends('layouts.app')

@section('title', 'Edit Akun: ' . $user->name)
@section('page_title', 'Perbarui Akun Pengguna')
@section('page_subtitle', 'Edit informasi akun ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <div class="flex items-center justify-between pb-5 border-b border-gray-100 mb-6">
            <div>
                <h3 class="text-base font-bold text-gray-800">Edit Akun: {{ $user->name }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">Perbarui nama, peran, status, atau reset kata sandi</p>
            </div>
            <a href="{{ route('users.index') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i>
                <span>Kembali</span>
            </a>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

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
                           value="{{ old('name', $user->name) }}" 
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
                           value="{{ old('email', $user->email) }}" 
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
                        <option value="pengelola" {{ old('role', $user->role) === 'pengelola' ? 'selected' : '' }}>Pengelola (Akses Penuh: Kandang &amp; Akun)</option>
                        <option value="pegawai" {{ old('role', $user->role) === 'pegawai' ? 'selected' : '' }}>Pegawai (Akses Operasional: Kandang, Kambing, Medis)</option>
                    </select>
                </div>
                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password (Optional when updating) -->
            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Kata Sandi Baru (Kosongkan jika tidak ingin mengubah)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-lock text-xs"></i>
                    </div>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           placeholder="Minimal 6 karakter jika ingin mengganti" 
                           class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('password') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/50' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all">
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif Checkbox -->
            <div class="pt-1">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" 
                           name="status_aktif" 
                           value="1" 
                           {{ old('status_aktif', $user->status_aktif) ? 'checked' : '' }} 
                           class="w-4 h-4 text-[#00875a] focus:ring-[#00875a] rounded border-gray-300">
                    <span class="text-xs sm:text-sm font-semibold text-gray-700">Akun Aktif (Dapat digunakan untuk login ke sistem)</span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('users.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs sm:text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                    <i class="fa-solid fa-arrows-rotate text-xs"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
