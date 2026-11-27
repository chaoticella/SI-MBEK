@extends('layouts.app')

@section('title', 'Data Kandang')

@section('content')
<div class="space-y-6">
    <!-- Header Halaman Langsung di Fitur (Bukan di Navbar) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-1">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="text-2xl">🐐</span>
                <h1 class="text-2xl font-black text-stone-900 tracking-tight">Data Kandang</h1>
            </div>
            <p class="text-xs sm:text-sm text-stone-500 mt-1">
                Struktur lokasi peternakan, kapasitas maksimal, dan kategori peruntukan ternak.
            </p>
        </div>

        @if(auth()->user()->isPengelola())
            <a href="{{ route('kandang.create') }}" 
               class="inline-flex items-center justify-center gap-2 bg-[#0D4231] hover:bg-[#082E22] text-white px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-xs transition-all flex-shrink-0">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Kandang</span>
            </a>
        @endif
    </div>

    <!-- 5 Kartu Ringkasan (White Cards, Rounded Corners) -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 sm:gap-4">
        <!-- Total Kandang -->
        <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 shadow-2xs hover:border-[#6B9080] transition-colors">
            <p class="text-xs font-semibold text-stone-500">Total Blok</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-[#A3704C]">{{ $totalKandang }}</span>
                <span class="text-[11px] text-stone-400 font-bold">Blok</span>
            </div>
        </div>

        <!-- Total Kapasitas -->
        <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 shadow-2xs hover:border-[#0D4231] transition-colors">
            <p class="text-xs font-semibold text-stone-500">Total Kapasitas</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-[#0D4231]">{{ $totalKapasitas }}</span>
                <span class="text-[11px] text-stone-400 font-bold">Ekor</span>
            </div>
        </div>

        <!-- Kandang Jantan (Earth Brown) -->
        <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 shadow-2xs hover:border-[#A3704C] transition-colors">
            <p class="text-xs font-semibold text-stone-500">Kandang Jantan</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-[#A3704C]">{{ $kandangJantan }}</span>
                <span class="text-[11px] text-[#A3704C] bg-[#FAF7F2] border border-[#ECE5DA] px-2 py-0.5 rounded-lg font-bold">Pejantan</span>
            </div>
        </div>

        <!-- Kandang Betina (Sage Green) -->
        <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 shadow-2xs hover:border-[#6B9080] transition-colors">
            <p class="text-xs font-semibold text-stone-500">Kandang Betina</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-[#6B9080]">{{ $kandangBetina }}</span>
                <span class="text-[11px] text-[#6B9080] bg-[#FAF7F2] border border-[#ECE5DA] px-2 py-0.5 rounded-lg font-bold">Indukan</span>
            </div>
        </div>

        <!-- Kandang Isolasi -->
        <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 shadow-2xs hover:border-red-300 transition-colors col-span-2 md:col-span-1">
            <p class="text-xs font-semibold text-stone-500">Kandang Isolasi</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-red-600">{{ $kandangIsolasi }}</span>
                <span class="text-[11px] text-red-600 bg-red-50 border border-red-200 px-2 py-0.5 rounded-lg font-bold">Karantina</span>
            </div>
        </div>
    </div>

    <!-- Filter Card (Putih dengan rounded corners) -->
    <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 shadow-2xs">
        <form method="GET" action="{{ route('kandang.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <!-- Search Input -->
            <div class="sm:col-span-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400 text-xs">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Cari nama blok / keterangan..." 
                           class="w-full pl-9 pr-3 py-2 text-xs sm:text-sm rounded-xl border border-[#ECE5DA] bg-[#FAF7F2]/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all">
                </div>
            </div>

            <!-- Kategori Dropdown -->
            <div class="sm:col-span-3">
                <select name="kategori" class="w-full py-2 px-3 text-xs sm:text-sm rounded-xl border border-[#ECE5DA] bg-[#FAF7F2]/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all text-stone-700">
                    <option value="">Semua Kategori</option>
                    <option value="jantan" {{ $kategori === 'jantan' ? 'selected' : '' }}>Kambing Jantan</option>
                    <option value="betina" {{ $kategori === 'betina' ? 'selected' : '' }}>Kambing Betina</option>
                    <option value="isolasi" {{ $kategori === 'isolasi' ? 'selected' : '' }}>Isolasi / Sakit</option>
                </select>
            </div>

            <!-- Status Dropdown -->
            <div class="sm:col-span-3">
                <select name="status" class="w-full py-2 px-3 text-xs sm:text-sm rounded-xl border border-[#ECE5DA] bg-[#FAF7F2]/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6B9080] focus:border-[#0D4231] transition-all text-stone-700">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" 
                        class="w-full bg-[#2D2A26] hover:bg-black text-white py-2 px-4 rounded-xl text-xs sm:text-sm font-semibold transition-colors flex items-center justify-center gap-1.5 shadow-2xs">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filter</span>
                </button>

                @if($search || $kategori || $status)
                    <a href="{{ route('kandang.index') }}" 
                       title="Reset Filter"
                       class="p-2 bg-[#FAF7F2] hover:bg-[#F3EDE2] text-stone-600 border border-[#ECE5DA] rounded-xl text-xs flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table Minimalis dengan garis pembatas tipis -->
    <div class="bg-white rounded-3xl border border-[#ECE5DA] shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-[#F5F0E6]/70 border-b border-[#ECE5DA] text-stone-600 uppercase text-[11px] font-bold tracking-wider">
                        <th class="py-3.5 px-5">Blok Kandang</th>
                        <th class="py-3.5 px-5">Kapasitas Maksimal</th>
                        <th class="py-3.5 px-5">Kategori Kandang</th>
                        <th class="py-3.5 px-5">Status Operasional</th>
                        <th class="py-3.5 px-5">Keterangan / Lokasi</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#ECE5DA]">
                    @forelse($kandangs as $kandang)
                        <tr class="hover:bg-[#FAF7F2] transition-colors {{ $kandang->kategori === 'isolasi' ? 'bg-red-50/20' : '' }}">
                            <!-- Blok Kandang -->
                            <td class="py-3.5 px-5 font-bold text-stone-900">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $kandang->kategori === 'isolasi' ? 'bg-red-500 ring-2 ring-red-200' : ($kandang->kategori === 'jantan' ? 'bg-[#A3704C]' : 'bg-[#6B9080]') }}"></span>
                                    <span>{{ $kandang->blok_kandang }}</span>
                                    @if($kandang->kategori === 'isolasi')
                                        <span class="bg-red-100 text-red-700 text-[10px] font-black px-1.5 py-0.5 rounded tracking-wider uppercase">
                                            ISOLASI
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Kapasitas -->
                            <td class="py-3.5 px-5">
                                <span class="font-black text-stone-900">{{ $kandang->kapasitas_maksimal }}</span>
                                <span class="text-xs text-stone-400 font-medium">Ekor</span>
                            </td>

                            <!-- Kategori -->
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $kandang->kategori_badge['bg'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $kandang->kategori_badge['dot'] }}"></span>
                                    {{ $kandang->kategori_label }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-5">
                                @if($kandang->status_aktif)
                                    <span class="inline-flex items-center gap-1 bg-[#E8F3EE] text-[#0D4231] border border-[#C5E2D4] text-xs px-2.5 py-0.5 rounded-full font-semibold">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-stone-100 text-stone-600 border border-stone-200 text-xs px-2.5 py-0.5 rounded-full font-semibold">
                                        <i class="fa-solid fa-circle-minus text-[10px]"></i>
                                        <span>Nonaktif</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Keterangan -->
                            <td class="py-3.5 px-5 text-stone-500 max-w-xs truncate">
                                {{ $kandang->keterangan ?? '—' }}
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-5 text-right font-medium">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('kandang.show', $kandang->id) }}" 
                                       class="text-stone-600 hover:text-stone-900 text-xs font-semibold hover:underline">
                                        Detail
                                    </a>

                                    @if(auth()->user()->isPengelola())
                                        <a href="{{ route('kandang.edit', $kandang->id) }}" 
                                           class="text-[#8D5B4C] hover:text-[#6F4E37] text-xs font-semibold hover:underline">
                                            Edit
                                        </a>

                                        <!-- Toggle Status -->
                                        <form action="{{ route('kandang.toggle-status', $kandang->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    onclick="return confirm('Apakah Anda yakin ingin {{ $kandang->status_aktif ? 'menonaktifkan' : 'mengaktifkan' }} kandang {{ $kandang->blok_kandang }}?')"
                                                    class="text-xs font-semibold {{ $kandang->status_aktif ? 'text-amber-700 hover:text-amber-900' : 'text-[#0D4231] hover:text-[#165B43]' }} hover:underline">
                                                {{ $kandang->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <!-- Hapus -->
                                        <form action="{{ route('kandang.destroy', $kandang->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Hapus permanen {{ $kandang->blok_kandang }} dari sistem?')"
                                                    class="text-red-500 hover:text-red-700 text-xs font-semibold hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-stone-400 text-xs">
                                <span class="text-4xl block mb-2">🐐</span>
                                <p class="text-sm font-semibold text-stone-600">Tidak ada data kandang ditemukan</p>
                                <p class="text-xs text-stone-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter kategori.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kandangs->hasPages())
            <div class="p-4 border-t border-[#ECE5DA] bg-[#FAF7F2]/50">
                {{ $kandangs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
