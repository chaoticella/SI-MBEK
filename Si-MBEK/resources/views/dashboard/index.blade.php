@extends('layouts.app')

@section('title', 'Dashboard Peternakan')

@section('content')
<div class="space-y-6">
    <!-- Header Halaman Langsung di Konten (Bukan di Navbar) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="text-2xl">🐐</span>
                <h1 class="text-2xl font-black text-stone-900 tracking-tight">Dashboard Peternakan</h1>
            </div>
            <p class="text-xs sm:text-sm text-stone-500 mt-1">
                Ringkasan operasional populasi kambing, struktur kandang, dan pemantauan kesehatan ternak.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('kandang.index') }}" 
               class="px-4 py-2.5 rounded-xl bg-[#0D4231] hover:bg-[#082E22] text-white text-xs sm:text-sm font-semibold shadow-xs transition-all flex items-center gap-2">
                <i class="fa-solid fa-warehouse text-xs"></i>
                <span>Lihat Data Kandang</span>
            </a>

            @if(auth()->user()->isPengelola())
                <a href="{{ route('kandang.create') }}" 
                   class="px-4 py-2.5 rounded-xl bg-white hover:bg-stone-50 border border-[#ECE5DA] text-stone-700 text-xs sm:text-sm font-semibold shadow-2xs transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus text-[#0D4231] text-xs"></i>
                    <span>Tambah Blok</span>
                </a>
            @endif
        </div>
    </div>

    <!-- 5 Kartu Ringkasan (Putih dengan Rounded Corners) -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
        <!-- Card 1: Total Kandang -->
        <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 sm:p-5 shadow-2xs hover:border-[#6B9080] transition-colors">
            <p class="text-xs font-semibold text-stone-500">Total Blok Kandang</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-stone-900">{{ $totalKandang }}</span>
                <span class="text-xs font-bold text-[#6B9080] bg-[#FAF7F2] border border-[#ECE5DA] px-2 py-0.5 rounded-lg">Blok</span>
            </div>
        </div>

        <!-- Card 2: Total Kapasitas -->
        <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 sm:p-5 shadow-2xs hover:border-[#6B9080] transition-colors">
            <p class="text-xs font-semibold text-stone-500">Kapasitas Maksimal</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-[#0D4231]">{{ $totalKapasitas }}</span>
                <span class="text-xs font-medium text-stone-400">Ekor</span>
            </div>
        </div>

        <!-- Card 3: Kandang Jantan (Earth Brown) -->
        <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 sm:p-5 shadow-2xs hover:border-[#A3704C] transition-colors">
            <p class="text-xs font-semibold text-stone-500">Kandang Jantan</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-[#A3704C]">{{ $kandangJantan }}</span>
                <span class="text-xs font-bold text-[#A3704C] bg-[#FAF7F2] border border-[#ECE5DA] px-2 py-0.5 rounded-lg">Pejantan</span>
            </div>
        </div>

        <!-- Card 4: Kandang Betina (Sage Green) -->
        <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 sm:p-5 shadow-2xs hover:border-[#6B9080] transition-colors">
            <p class="text-xs font-semibold text-stone-500">Kandang Betina</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-[#6B9080]">{{ $kandangBetina }}</span>
                <span class="text-xs font-bold text-[#6B9080] bg-[#FAF7F2] border border-[#ECE5DA] px-2 py-0.5 rounded-lg">Indukan</span>
            </div>
        </div>

        <!-- Card 5: Kandang Isolasi -->
        <div class="bg-white rounded-2xl border border-[#ECE5DA] p-4 sm:p-5 shadow-2xs hover:border-red-300 transition-colors col-span-2 lg:col-span-1">
            <p class="text-xs font-semibold text-stone-500">Kandang Isolasi</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-red-600">{{ $kandangIsolasi }}</span>
                <span class="text-xs font-bold text-red-700 bg-red-50 border border-red-200 px-2 py-0.5 rounded-lg">Medis</span>
            </div>
        </div>
    </div>

    <!-- Section Charts (Sage Green & Earth Brown) & Farm Elements -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Chart 1: Visualisasi Kapasitas per Blok Kandang (Bar Chart: Sage Green & Earth Brown) -->
        <div class="lg:col-span-7 bg-white rounded-3xl border border-[#ECE5DA] p-6 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-[#ECE5DA]">
                    <div>
                        <h3 class="text-base font-bold text-stone-900">Distribusi Kapasitas Kandang</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Perbandingan kapasitas tampung antar blok peternakan</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-semibold">
                        <span class="flex items-center gap-1.5 text-stone-600">
                            <span class="w-3 h-3 rounded-md bg-[#6B9080]"></span>
                            <span>Sage Green</span>
                        </span>
                        <span class="flex items-center gap-1.5 text-stone-600">
                            <span class="w-3 h-3 rounded-md bg-[#A3704C]"></span>
                            <span>Earth Brown</span>
                        </span>
                    </div>
                </div>

                <!-- Custom Visual Bars -->
                <div class="space-y-4 pt-5">
                    @forelse($recentKandangs as $index => $item)
                        @php
                            $persen = min(100, max(15, ($item->kapasitas_maksimal / max(1, $totalKapasitas ?: 100)) * 250));
                            $barColor = $item->kategori === 'jantan' ? 'bg-[#A3704C]' : ($item->kategori === 'betina' ? 'bg-[#6B9080]' : 'bg-red-500');
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs font-semibold mb-1">
                                <span class="flex items-center gap-2 text-stone-800">
                                    <span class="w-2 h-2 rounded-full {{ $item->kategori === 'isolasi' ? 'bg-red-500' : ($item->kategori === 'jantan' ? 'bg-[#A3704C]' : 'bg-[#6B9080]') }}"></span>
                                    <span>{{ $item->blok_kandang }} ({{ ucfirst($item->kategori) }})</span>
                                </span>
                                <span class="text-stone-500">{{ $item->kapasitas_maksimal }} Ekor</span>
                            </div>
                            <div class="w-full bg-[#FAF7F2] border border-[#ECE5DA] rounded-full h-3.5 overflow-hidden p-0.5">
                                <div class="{{ $barColor }} h-full rounded-full transition-all duration-500" style="width: {{ $persen }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-stone-400 py-4 text-center">Belum ada data kandang.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-[#ECE5DA] flex items-center justify-between text-xs text-stone-500">
                <span>Total kapasitas peternakan: <strong class="text-stone-800">{{ $totalKapasitas }} Ekor</strong></span>
                <span class="flex items-center gap-1 text-[#0D4231] font-semibold">
                    <i class="fa-solid fa-leaf text-[#6B9080]"></i>
                    <span>Terkendali &amp; Higienis</span>
                </span>
            </div>
        </div>

        <!-- Chart 2: Komposisi Peruntukan Ternak & Ilustrasi Kambing -->
        <div class="lg:col-span-5 bg-white rounded-3xl border border-[#ECE5DA] p-6 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-[#ECE5DA]">
                    <div>
                        <h3 class="text-base font-bold text-stone-900">Komposisi Peruntukan</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Persentase kategori blok peternakan</p>
                    </div>
                    <span class="text-xl">🌾</span>
                </div>

                <!-- Donut / Pie Segment Chart in SVG (Sage Green & Earth Brown) -->
                <div class="my-5 flex flex-col items-center justify-center">
                    <div class="relative w-40 h-40 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <!-- Background ring -->
                            <path class="text-[#FAF7F2]" stroke-width="4.5" stroke="currentColor" fill="none"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            
                            @php
                                $totalB = max(1, $totalKandang);
                                $pBetina = ($kandangBetina / $totalB) * 100;
                                $pJantan = ($kandangJantan / $totalB) * 100;
                                $pIsolasi = ($kandangIsolasi / $totalB) * 100;
                                $offset1 = 0;
                                $offset2 = $pBetina;
                                $offset3 = $pBetina + $pJantan;
                            @endphp

                            <!-- Segment 1: Betina (Sage Green #6B9080) -->
                            <path class="text-[#6B9080]" stroke-dasharray="{{ $pBetina }}, 100" stroke-dashoffset="0" stroke-width="4.5" stroke="currentColor" fill="none"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />

                            <!-- Segment 2: Jantan (Earth Brown #A3704C) -->
                            <path class="text-[#A3704C]" stroke-dasharray="{{ $pJantan }}, 100" stroke-dashoffset="-{{ $offset2 }}" stroke-width="4.5" stroke="currentColor" fill="none"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />

                            <!-- Segment 3: Isolasi (Terracotta / Red #DC2626) -->
                            @if($kandangIsolasi > 0)
                            <path class="text-red-500" stroke-dasharray="{{ $pIsolasi }}, 100" stroke-dashoffset="-{{ $offset3 }}" stroke-width="4.5" stroke="currentColor" fill="none"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            @endif
                        </svg>

                        <!-- Center Goat Element -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                            <span class="text-2xl">🐐</span>
                            <span class="text-xs font-bold text-stone-800">{{ $totalKandang }} Blok</span>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="grid grid-cols-3 gap-2 w-full mt-4 text-center text-xs">
                        <div class="p-2 rounded-xl bg-[#FAF7F2] border border-[#ECE5DA]">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#6B9080] mb-1"></span>
                            <p class="font-bold text-stone-800">{{ $kandangBetina }}</p>
                            <p class="text-[10px] text-stone-500">Betina</p>
                        </div>
                        <div class="p-2 rounded-xl bg-[#FAF7F2] border border-[#ECE5DA]">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#A3704C] mb-1"></span>
                            <p class="font-bold text-stone-800">{{ $kandangJantan }}</p>
                            <p class="text-[10px] text-stone-500">Jantan</p>
                        </div>
                        <div class="p-2 rounded-xl bg-[#FAF7F2] border border-[#ECE5DA]">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-red-500 mb-1"></span>
                            <p class="font-bold text-stone-800">{{ $kandangIsolasi }}</p>
                            <p class="text-[10px] text-stone-500">Isolasi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Simple Farm Illustration Banner -->
            <div class="p-3.5 rounded-2xl bg-[#F7F3EB] border border-[#ECE5DA] flex items-center gap-3 text-xs text-stone-600">
                <span class="text-xl">🏡</span>
                <p class="leading-tight">
                    Struktur kandang peternakan terorganisir untuk sirkulasi udara optimal dan pencegahan penularan penyakit.
                </p>
            </div>
        </div>
    </div>

    <!-- Tabel Minimalis Struktur Blok Kandang -->
    <div class="bg-white rounded-3xl border border-[#ECE5DA] shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-[#ECE5DA] flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-base">📋</span>
                <h4 class="font-bold text-stone-900 text-sm sm:text-base">Daftar Blok Kandang Aktif</h4>
            </div>
            <a href="{{ route('kandang.index') }}" class="text-xs font-bold text-[#0D4231] hover:text-[#165B43] flex items-center gap-1.5 transition-colors">
                <span>Lihat Semua Kandang</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-[#F5F0E6]/70 border-b border-[#ECE5DA] text-stone-600 uppercase text-[11px] font-bold tracking-wider">
                        <th class="py-3 px-5">Blok Kandang</th>
                        <th class="py-3 px-5">Kapasitas</th>
                        <th class="py-3 px-5">Kategori</th>
                        <th class="py-3 px-5">Status</th>
                        <th class="py-3 px-5">Keterangan</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#ECE5DA]">
                    @forelse($recentKandangs as $item)
                        <tr class="hover:bg-[#FAF7F2] transition-colors {{ $item->kategori === 'isolasi' ? 'bg-red-50/20' : '' }}">
                            <td class="py-3.5 px-5 font-bold text-stone-900">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $item->kategori === 'isolasi' ? 'bg-red-500' : ($item->kategori === 'jantan' ? 'bg-[#A3704C]' : 'bg-[#6B9080]') }}"></span>
                                    <span>{{ $item->blok_kandang }}</span>
                                    @if($item->kategori === 'isolasi')
                                        <span class="bg-red-100 text-red-700 text-[10px] font-bold px-1.5 py-0.2 rounded uppercase">Isolasi</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="font-black text-stone-800">{{ $item->kapasitas_maksimal }}</span>
                                <span class="text-xs text-stone-400">ekor</span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $item->kategori_badge['bg'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $item->kategori_badge['dot'] }}"></span>
                                    {{ $item->kategori_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5">
                                @if($item->status_aktif)
                                    <span class="inline-block bg-[#E8F3EE] text-[#0D4231] border border-[#C5E2D4] text-xs px-2.5 py-0.5 rounded-full font-semibold">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-block bg-stone-100 text-stone-600 border border-stone-200 text-xs px-2.5 py-0.5 rounded-full font-semibold">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-stone-500 text-xs max-w-xs truncate">
                                {{ $item->keterangan ?? '—' }}
                            </td>
                            <td class="py-3.5 px-5 text-right font-medium">
                                <a href="{{ route('kandang.show', $item->id) }}" class="text-[#0D4231] hover:underline mr-2 text-xs font-semibold">Detail</a>
                                @if(auth()->user()->isPengelola())
                                    <a href="{{ route('kandang.edit', $item->id) }}" class="text-[#8D5B4C] hover:underline text-xs font-semibold">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-stone-400 text-xs">
                                Belum ada data kandang yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
