@extends('layouts.app')

@section('title', 'Data Kandang')
@section('page_title', 'Data Kandang')
@section('page_subtitle', 'Struktur lokasi peternakan, kapasitas maksimal, dan kategori peruntukan ternak')

@section('content')
<div class="space-y-6">
    <!-- Top Alert Banners (Matching Reference Design) -->
    <div class="space-y-3">
        @if($kandangIsolasi > 0)
            <div class="p-4 rounded-2xl bg-red-50/90 border border-red-200/80 flex items-start justify-between text-xs sm:text-sm text-red-900 shadow-xs">
                <div class="flex items-start sm:items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-red-500 inline-block mt-1 sm:mt-0 flex-shrink-0 animate-pulse"></span>
                    <div>
                        <span class="font-bold text-red-800">2 kambing sakit di kandang isolasi perlu dipantau:</span>
                        <div class="inline-flex flex-wrap gap-2 mt-1 sm:mt-0 sm:ml-2">
                            <span class="bg-white/80 border border-red-200 text-red-800 px-2 py-0.5 rounded-md text-xs font-semibold">KB-002 - Blok 3 (Isolasi) &bull; hari ke-7</span>
                            <span class="bg-white/80 border border-red-200 text-red-800 px-2 py-0.5 rounded-md text-xs font-semibold">KB-005 - Blok 3 (Isolasi) &bull; hari ke-5</span>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-700 text-lg leading-none">&times;</button>
            </div>
        @endif

        <div class="p-4 rounded-2xl bg-amber-50/90 border border-amber-200/80 flex items-center justify-between text-xs sm:text-sm text-amber-900 shadow-xs">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-base"></i>
                <div>
                    <span class="font-medium text-amber-800">1 kambing sakit belum di kandang isolasi:</span>
                    <span class="font-bold text-amber-900 ml-1">KB-007</span>
                    <span class="text-xs text-amber-700 ml-1">(Kacang - Blok 2)</span>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-amber-400 hover:text-amber-700 text-lg leading-none">&times;</button>
        </div>
    </div>

    <!-- Summary Stat Cards (Exact match with screenshot 1 & 2) -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <!-- Total Kandang -->
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs font-medium text-gray-500">Total Blok Kandang</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-amber-500">{{ $totalKandang }}</span>
                <span class="text-[11px] text-gray-400 font-semibold">Blok</span>
            </div>
        </div>

        <!-- Total Kapasitas -->
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs font-medium text-gray-500">Total Kapasitas</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-[#00875a]">{{ $totalKapasitas }}</span>
                <span class="text-[11px] text-gray-400 font-semibold">Ekor</span>
            </div>
        </div>

        <!-- Kandang Jantan -->
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs font-medium text-gray-500">Kandang Jantan</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-blue-600">{{ $kandangJantan }}</span>
                <span class="text-[11px] text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md font-semibold">Blok</span>
            </div>
        </div>

        <!-- Kandang Betina -->
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs font-medium text-gray-500">Kandang Betina</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-pink-500">{{ $kandangBetina }}</span>
                <span class="text-[11px] text-pink-600 bg-pink-50 px-2 py-0.5 rounded-md font-semibold">Blok</span>
            </div>
        </div>

        <!-- Kandang Isolasi / Sakit -->
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow col-span-2 md:col-span-1">
            <p class="text-xs font-medium text-gray-500">Kandang Isolasi</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-red-500">{{ $kandangIsolasi }}</span>
                <span class="text-[11px] text-red-600 bg-red-50 px-2 py-0.5 rounded-md font-semibold">Sakit</span>
            </div>
        </div>
    </div>

    <!-- Description & Action Button Row -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-1">
        <div>
            <p class="text-xs sm:text-sm text-gray-600">
                Struktur penempatan lokasi peternakan, kapasitas maksimum tampung, dan kategori peruntukan blok.
            </p>
        </div>

        @if(auth()->user()->isPengelola())
            <a href="{{ route('kandang.create') }}" 
               class="inline-flex items-center justify-center gap-2 bg-[#00875a] hover:bg-[#00734c] text-white px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-sm transition-all flex-shrink-0">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Kandang</span>
            </a>
        @endif
    </div>

    <!-- Search & Filter Card (Style matching the reference screenshot filter bar) -->
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <form method="GET" action="{{ route('kandang.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <!-- Search Input -->
            <div class="sm:col-span-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 text-xs">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Cari nama blok / keterangan..." 
                           class="w-full pl-9 pr-3 py-2 text-xs sm:text-sm rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all">
                </div>
            </div>

            <!-- Kategori Dropdown -->
            <div class="sm:col-span-3">
                <select name="kategori" class="w-full py-2 px-3 text-xs sm:text-sm rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all text-gray-700">
                    <option value="">Semua Kategori</option>
                    <option value="jantan" {{ $kategori === 'jantan' ? 'selected' : '' }}>Kambing Jantan</option>
                    <option value="betina" {{ $kategori === 'betina' ? 'selected' : '' }}>Kambing Betina</option>
                    <option value="isolasi" {{ $kategori === 'isolasi' ? 'selected' : '' }}>Isolasi / Sakit</option>
                </select>
            </div>

            <!-- Status Dropdown -->
            <div class="sm:col-span-3">
                <select name="status" class="w-full py-2 px-3 text-xs sm:text-sm rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-[#00875a] transition-all text-gray-700">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" 
                        class="w-full bg-gray-900 hover:bg-black text-white py-2 px-4 rounded-xl text-xs sm:text-sm font-semibold transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filter</span>
                </button>

                @if($search || $kategori || $status)
                    <a href="{{ route('kandang.index') }}" 
                       title="Reset Filter"
                       class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table (Clean, rounded card container with reference styling) -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-gray-500 uppercase text-[11px] font-bold tracking-wider">
                        <th class="py-3.5 px-5">Blok Kandang</th>
                        <th class="py-3.5 px-5">Kapasitas Maksimal</th>
                        <th class="py-3.5 px-5">Kategori Kandang</th>
                        <th class="py-3.5 px-5">Status Operasional</th>
                        <th class="py-3.5 px-5">Keterangan / Lokasi</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kandangs as $kandang)
                        <tr class="hover:bg-gray-50/70 transition-colors {{ $kandang->kategori === 'isolasi' ? 'bg-red-50/30' : '' }}">
                            <!-- Blok Kandang -->
                            <td class="py-3.5 px-5 font-bold text-gray-800">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $kandang->kategori === 'isolasi' ? 'bg-red-500 ring-2 ring-red-200' : ($kandang->kategori === 'jantan' ? 'bg-blue-500' : 'bg-pink-500') }}"></span>
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
                                <span class="font-extrabold text-gray-800">{{ $kandang->kapasitas_maksimal }}</span>
                                <span class="text-xs text-gray-400 font-medium">Ekor</span>
                            </td>

                            <!-- Kategori -->
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $kandang->kategori_badge['bg'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $kandang->kategori_badge['dot'] }}"></span>
                                    {{ $kandang->kategori_label }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-5">
                                @if($kandang->status_aktif)
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs px-2.5 py-0.5 rounded-full font-semibold">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 border border-gray-200 text-xs px-2.5 py-0.5 rounded-full font-semibold">
                                        <i class="fa-solid fa-circle-minus text-[10px]"></i>
                                        <span>Nonaktif</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Keterangan -->
                            <td class="py-3.5 px-5 text-gray-600 max-w-xs truncate">
                                {{ $kandang->keterangan ?? '-' }}
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-5 text-right font-medium">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('kandang.show', $kandang->id) }}" 
                                       class="text-gray-600 hover:text-gray-900 text-xs font-semibold hover:underline">
                                        Detail
                                    </a>

                                    @if(auth()->user()->isPengelola())
                                        <a href="{{ route('kandang.edit', $kandang->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-xs font-semibold hover:underline">
                                            Edit
                                        </a>

                                        <!-- Toggle Status (Menonaktifkan / Mengaktifkan Kandang) -->
                                        <form action="{{ route('kandang.toggle-status', $kandang->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    onclick="return confirm('Apakah Anda yakin ingin {{ $kandang->status_aktif ? 'menonaktifkan' : 'mengaktifkan' }} kandang {{ $kandang->blok_kandang }}?')"
                                                    class="text-xs font-semibold {{ $kandang->status_aktif ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-600 hover:text-emerald-800' }} hover:underline">
                                                {{ $kandang->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <!-- Hapus Kandang -->
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
                            <td colspan="6" class="py-12 text-center text-gray-400 text-xs">
                                <i class="fa-solid fa-warehouse text-4xl mb-2 text-gray-300"></i>
                                <p class="text-sm font-semibold text-gray-500">Tidak ada data kandang ditemukan</p>
                                <p class="text-xs text-gray-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter kategori Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kandangs->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $kandangs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
