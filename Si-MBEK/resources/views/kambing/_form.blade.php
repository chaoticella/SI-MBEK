@php
    $inputBase = 'w-full px-4 py-2.5 text-xs sm:text-sm rounded-xl border focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all';
    $labelClass = 'block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5';
@endphp

<!-- Kode Kambing -->
<div>
    <label for="kode_kambing" class="{{ $labelClass }}">ID Kambing <span class="text-red-500">*</span></label>
    <input type="text" name="kode_kambing" id="kode_kambing"
           value="{{ old('kode_kambing', $kambing?->kode_kambing) }}"
           placeholder="Contoh: KMB-001" required
           class="{{ $inputBase }} {{ $errors->has('kode_kambing') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/50' }}">
    @error('kode_kambing')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- Ras -->
<div>
    <label for="ras" class="{{ $labelClass }}">Jenis / Ras <span class="text-red-500">*</span></label>
    <input type="text" name="ras" id="ras"
           value="{{ old('ras', $kambing?->ras) }}"
           placeholder="Contoh: Etawa, Boer, Kacang" required
           class="{{ $inputBase }} {{ $errors->has('ras') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/50' }}">
    @error('ras')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <!-- Jenis Kelamin -->
    <div>
        <label for="jenis_kelamin" class="{{ $labelClass }}">Jenis Kelamin <span class="text-red-500">*</span></label>
        <select name="jenis_kelamin" id="jenis_kelamin" required
                class="{{ $inputBase }} text-gray-700 {{ $errors->has('jenis_kelamin') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/50' }}">
            <option value="" disabled {{ old('jenis_kelamin', $kambing?->jenis_kelamin) ? '' : 'selected' }}>-- Pilih --</option>
            <option value="jantan" {{ old('jenis_kelamin', $kambing?->jenis_kelamin) === 'jantan' ? 'selected' : '' }}>Jantan</option>
            <option value="betina" {{ old('jenis_kelamin', $kambing?->jenis_kelamin) === 'betina' ? 'selected' : '' }}>Betina</option>
        </select>
        @error('jenis_kelamin')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Tanggal Lahir -->
    <div>
        <label for="tanggal_lahir" class="{{ $labelClass }}">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" id="tanggal_lahir"
               value="{{ old('tanggal_lahir', $kambing?->tanggal_lahir?->format('Y-m-d')) }}"
               max="{{ now()->format('Y-m-d') }}"
               class="{{ $inputBase }} text-gray-700 {{ $errors->has('tanggal_lahir') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/50' }}">
        @error('tanggal_lahir')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <!-- Status -->
    <div>
        <label for="status" class="{{ $labelClass }}">Status <span class="text-red-500">*</span></label>
        <select name="status" id="status" required
                class="{{ $inputBase }} text-gray-700 {{ $errors->has('status') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/50' }}">
            <option value="" disabled {{ old('status', $kambing?->status) ? '' : 'selected' }}>-- Pilih --</option>
            <option value="indukan" {{ old('status', $kambing?->status) === 'indukan' ? 'selected' : '' }}>Indukan</option>
            <option value="anakan" {{ old('status', $kambing?->status) === 'anakan' ? 'selected' : '' }}>Anakan</option>
            <option value="siap_jual" {{ old('status', $kambing?->status) === 'siap_jual' ? 'selected' : '' }}>Siap Jual</option>
        </select>
        @error('status')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Kandang -->
    <div>
        <label for="kandang_id" class="{{ $labelClass }}">Kandang <span class="text-red-500">*</span></label>
        <select name="kandang_id" id="kandang_id" required
                class="{{ $inputBase }} text-gray-700 {{ $errors->has('kandang_id') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/50' }}">
            <option value="" disabled {{ old('kandang_id', $kambing?->kandang_id) ? '' : 'selected' }}>-- Pilih Kandang --</option>
            @foreach($kandangs as $kandang)
                <option value="{{ $kandang->id }}" {{ (string) old('kandang_id', $kambing?->kandang_id) === (string) $kandang->id ? 'selected' : '' }}>
                    {{ $kandang->blok_kandang }} ({{ $kandang->kategori_label }})
                </option>
            @endforeach
        </select>
        @error('kandang_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Submit Buttons -->
<div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
    <a href="{{ route('kambing.index') }}"
       class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs sm:text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
        Batal
    </a>
    <button type="submit"
            class="px-6 py-2.5 rounded-xl bg-[#00875a] hover:bg-[#00734c] text-white text-xs sm:text-sm font-bold shadow-sm transition-all flex items-center gap-2">
        <i class="fa-solid fa-floppy-disk text-xs"></i>
        <span>{{ $submitLabel }}</span>
    </button>
</div>