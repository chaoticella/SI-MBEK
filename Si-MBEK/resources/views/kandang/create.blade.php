@extends('layouts.app')

@section('title', 'Tambah Kandang Baru')
@section('page_title', 'Tambah Blok Kandang Baru')
@section('page_subtitle', 'Tambahkan lokasi, kapasitas, dan peruntukan kategori kandang')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <div class="flex items-center justify-between pb-5 border-b border-gray-100 mb-6">
            <div>
                <h3 class="text-base font-bold text-gray-800">Formulir Data Kandang</h3>
                <p class="text-xs text-gray-500 mt-0.5">Isi detail blok kandang peternakan dengan lengkap</p>
            </div>
            <a href="{{ route('kandang.index') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i>
                <span>Kembali</span>
            </a>
        </div>

        <form action="{{ route('kandang.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Blok Kandang -->
            <div>
                <label for="blok_kandang" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Nama / Nomor Blok Kandang <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-warehouse text-xs"></i>
                    </div>
                    <input type="text" 
                           name="blok_kandang" 
                           id="blok_kandang" 
                           value="{{ old('blok_kandang') }}" 
                           placeholder="Contoh: Blok 6, Blok A-1, dll." 
                           required 
                           class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('blok_kandang') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/50' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all">
                </div>
                @error('blok_kandang')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kapasitas Maksimal -->
            <div>
                <label for="kapasitas_maksimal" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Kapasitas Maksimal (Ekor) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-hashtag text-xs"></i>
                    </div>
                    <input type="number" 
                           name="kapasitas_maksimal" 
                           id="kapasitas_maksimal" 
                           value="{{ old('kapasitas_maksimal') }}" 
                           placeholder="Contoh: 25" 
                           min="1" 
                           max="1000" 
                           required 
                           class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('kapasitas_maksimal') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/50' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all">
                </div>
                @error('kapasitas_maksimal')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori Kandang -->
            <div>
                <label for="kategori" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Kategori Peruntukan Kandang <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-tags text-xs"></i>
                    </div>
                    <select name="kategori" 
                            id="kategori" 
                            required 
                            class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('kategori') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/50' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all text-gray-700">
                        <option value="" disabled selected>-- Pilih Kategori Kandang --</option>
                        <option value="jantan" {{ old('kategori') === 'jantan' ? 'selected' : '' }}>Kambing Jantan (Pejantan / Bibit)</option>
                        <option value="betina" {{ old('kategori') === 'betina' ? 'selected' : '' }}>Kambing Betina (Indukan / Bunting)</option>
                        <option value="isolasi" {{ old('kategori') === 'isolasi' ? 'selected' : '' }}>Isolasi / Sakit (Karantina Medis)</option>
                    </select>
                </div>
                @error('kategori')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif Checkbox -->
            <div class="pt-1">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" 
                           name="status_aktif" 
                           value="1" 
                           {{ old('status_aktif', '1') ? 'checked' : '' }} 
                           class="w-4 h-4 text-[#00875a] focus:ring-[#00875a] rounded border-gray-300">
                    <span class="text-xs sm:text-sm font-semibold text-gray-700">Kandang Aktif (Dapat digunakan untuk penempatan ternak)</span>
                </label>
            </div>

            <!-- Keterangan Tambahan -->
            <div>
                <label for="keterangan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Keterangan Tambahan (Opsional)
                </label>
                <textarea name="keterangan" 
                          id="keterangan" 
                          rows="3" 
                          placeholder="Catatan kondisi fasilitas, sirkulasi udara, atau ras kambing khusus..." 
                          class="w-full p-3 text-xs sm:text-sm rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('kandang.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs sm:text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-[#00875a] hover:bg-[#00734c] text-white text-xs sm:text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Kandang</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
