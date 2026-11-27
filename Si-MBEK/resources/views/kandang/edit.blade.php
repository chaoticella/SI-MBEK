@extends('layouts.app')

@section('title', 'Perbarui Kandang: ' . $kandang->blok_kandang)

@section('content')
<div class="max-w-2xl mx-auto space-y-5">
    <!-- Header Halaman Langsung di Konten -->
    <div class="flex items-center justify-between pb-1">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xl">🐐</span>
                <h1 class="text-xl font-black text-stone-900 tracking-tight">Perbarui Data Kandang</h1>
            </div>
            <p class="text-xs text-stone-500 mt-0.5">Edit detail {{ $kandang->blok_kandang }}</p>
        </div>

        <a href="{{ route('kandang.index') }}" 
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-[#ECE5DA] bg-white text-xs font-semibold text-stone-600 hover:bg-[#FAF7F2] transition-colors shadow-2xs">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Card Putih dengan Rounded Corners -->
    <div class="bg-white rounded-3xl border border-[#ECE5DA] shadow-2xs p-6 sm:p-8">
        <form action="{{ route('kandang.update', $kandang->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Blok Kandang -->
            <div>
                <label for="blok_kandang" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                    Nama / Nomor Blok Kandang <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                        <i class="fa-solid fa-warehouse text-xs"></i>
                    </div>
                    <input type="text" 
                           name="blok_kandang" 
                           id="blok_kandang" 
                           value="{{ old('blok_kandang', $kandang->blok_kandang) }}" 
                           required 
                           class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('blok_kandang') ? 'border-red-400 bg-red-50/40' : 'border-[#ECE5DA] bg-[#FAF7F2]/60' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all">
                </div>
                @error('blok_kandang')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kapasitas Maksimal -->
            <div>
                <label for="kapasitas_maksimal" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                    Kapasitas Maksimal (Ekor) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                        <i class="fa-solid fa-hashtag text-xs"></i>
                    </div>
                    <input type="number" 
                           name="kapasitas_maksimal" 
                           id="kapasitas_maksimal" 
                           value="{{ old('kapasitas_maksimal', $kandang->kapasitas_maksimal) }}" 
                           min="1" 
                           max="1000" 
                           required 
                           class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('kapasitas_maksimal') ? 'border-red-400 bg-red-50/40' : 'border-[#ECE5DA] bg-[#FAF7F2]/60' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all">
                </div>
                @error('kapasitas_maksimal')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori Kandang -->
            <div>
                <label for="kategori" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                    Kategori Peruntukan Kandang <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                        <i class="fa-solid fa-tags text-xs"></i>
                    </div>
                    <select name="kategori" 
                            id="kategori" 
                            required 
                            class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border {{ $errors->has('kategori') ? 'border-red-400 bg-red-50/40' : 'border-[#ECE5DA] bg-[#FAF7F2]/60' }} focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all text-stone-700">
                        <option value="jantan" {{ old('kategori', $kandang->kategori) === 'jantan' ? 'selected' : '' }}>Kambing Jantan (Pejantan / Bibit)</option>
                        <option value="betina" {{ old('kategori', $kandang->kategori) === 'betina' ? 'selected' : '' }}>Kambing Betina (Indukan / Bunting)</option>
                        <option value="isolasi" {{ old('kategori', $kandang->kategori) === 'isolasi' ? 'selected' : '' }}>Isolasi / Sakit (Karantina Medis)</option>
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
                           {{ old('status_aktif', $kandang->status_aktif) ? 'checked' : '' }} 
                           class="w-4 h-4 text-[#0D4231] focus:ring-[#0D4231] rounded border-[#ECE5DA]">
                    <span class="text-xs sm:text-sm font-semibold text-stone-700">Kandang Aktif (Operasional)</span>
                </label>
            </div>

            <!-- Keterangan -->
            <div>
                <label for="keterangan" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                    Keterangan Tambahan (Opsional)
                </label>
                <textarea name="keterangan" 
                          id="keterangan" 
                          rows="3" 
                          class="w-full p-3 text-xs sm:text-sm rounded-xl border border-[#ECE5DA] bg-[#FAF7F2]/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all">{{ old('keterangan', $kandang->keterangan) }}</textarea>
                @error('keterangan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons: Forest Green Button -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#ECE5DA]">
                <a href="{{ route('kandang.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-[#ECE5DA] text-xs sm:text-sm font-semibold text-stone-600 hover:bg-[#FAF7F2] transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-[#0D4231] hover:bg-[#082E22] text-white text-xs sm:text-sm font-bold shadow-xs transition-all flex items-center gap-2">
                    <i class="fa-solid fa-arrows-rotate text-xs"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
