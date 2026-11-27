@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Peternakan')
@section('page_subtitle', 'Ringkasan operasional dan struktur peternakan kambing SI-MBEK')

@section('content')
<div class="space-y-6">
    <!-- Top System Alerts (Matching the reference screenshots) -->
    <div class="space-y-3">
        <!-- Red Warning Alert -->
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

        <!-- Yellow Warning Alert -->
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

    <!-- Summary Stat Cards (Matching screenshot 1 & 2 styles) -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Card 1: Total Kandang -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs font-medium text-gray-500">Total Blok Kandang</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-gray-900">{{ $totalKandang }}</span>
                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Blok</span>
            </div>
        </div>

        <!-- Card 2: Total Kapasitas -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs font-medium text-gray-500">Kapasitas Maksimal</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-[#00875a]">{{ $totalKapasitas }}</span>
                <span class="text-xs font-medium text-gray-500">Ekor</span>
            </div>
        </div>

        <!-- Card 3: Kandang Jantan -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs font-medium text-gray-500">Kandang Jantan</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-blue-600">{{ $kandangJantan }}</span>
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">Blok</span>
            </div>
        </div>

        <!-- Card 4: Kandang Betina -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs font-medium text-gray-500">Kandang Betina</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-pink-500">{{ $kandangBetina }}</span>
                <span class="text-xs font-medium text-pink-600 bg-pink-50 px-2 py-0.5 rounded-md">Blok</span>
            </div>
        </div>

        <!-- Card 5: Kandang Isolasi / Sakit -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow col-span-2 lg:col-span-1">
            <p class="text-xs font-medium text-gray-500">Kandang Isolasi / Sakit</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-red-500">{{ $kandangIsolasi }}</span>
                <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-md">Karantina</span>
            </div>
        </div>
    </div>

    <!-- Modul 1 Overview Banner & Quick Actions -->
    <div class="bg-gradient-to-r from-[#014d33] to-[#036242] rounded-3xl p-6 sm:p-8 text-white shadow-md relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-block bg-amber-400 text-gray-900 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider mb-3">
                Modul 1: Manajemen Pengguna &amp; Kandang
            </span>
            <h3 class="text-xl sm:text-2xl font-bold leading-tight">
                Selamat Datang, {{ auth()->user()->name }}!
            </h3>
            <p class="text-emerald-100/90 text-xs sm:text-sm mt-2 leading-relaxed">
                Anda masuk sebagai peran <strong class="text-amber-300 capitalize">{{ auth()->user()->role_label }}</strong>. 
                Modul ini memfasilitasi pengelolaan hak akses pengguna, otentikasi login/logout, middleware pembatas role, 
                serta CRUD struktur data kandang peternakan kambing.
            </p>

            <div class="flex flex-wrap items-center gap-3 mt-5">
                <a href="{{ route('kandang.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-[#014d33] hover:bg-emerald-50 rounded-xl text-xs sm:text-sm font-bold shadow-sm transition-all">
                    <i class="fa-solid fa-warehouse"></i>
                    <span>Kelola Data Kandang</span>
                </a>

                @if(auth()->user()->isPengelola())
                    <a href="{{ route('users.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#00875a] text-white hover:bg-emerald-600 rounded-xl text-xs sm:text-sm font-bold shadow-sm transition-all border border-emerald-400/40">
                        <i class="fa-solid fa-user-gear"></i>
                        <span>Manajemen Pengguna</span>
                    </a>
                @endif
            </div>
        </div>

        <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none text-9xl">
            <i class="fa-solid fa-warehouse"></i>
        </div>
    </div>

    <!-- Table of Kandang Overview -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h4 class="font-bold text-gray-800 text-base">Struktur Blok Kandang Terdaftar</h4>
                <p class="text-xs text-gray-500 mt-0.5">Ringkasan kapasitas dan peruntukan lokasi kandang</p>
            </div>
            <a href="{{ route('kandang.index') }}" class="text-xs font-bold text-[#00875a] hover:text-[#014d33] flex items-center gap-1">
                <span>Lihat Semua Kandang</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-gray-100 text-gray-500 uppercase text-[11px] font-bold tracking-wider">
                        <th class="py-3 px-5">Blok Kandang</th>
                        <th class="py-3 px-5">Kapasitas</th>
                        <th class="py-3 px-5">Kategori</th>
                        <th class="py-3 px-5">Status</th>
                        <th class="py-3 px-5">Keterangan</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentKandangs as $item)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="py-3 px-5 font-bold text-gray-800">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $item->kategori === 'isolasi' ? 'bg-red-500' : ($item->kategori === 'jantan' ? 'bg-blue-500' : 'bg-pink-500') }}"></span>
                                    <span>{{ $item->blok_kandang }}</span>
                                    @if($item->kategori === 'isolasi')
                                        <span class="bg-red-100 text-red-700 text-[10px] font-bold px-1.5 py-0.2 rounded uppercase">Isolasi</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-5">
                                <span class="font-semibold text-gray-700">{{ $item->kapasitas_maksimal }}</span>
                                <span class="text-xs text-gray-400">ekor</span>
                            </td>
                            <td class="py-3 px-5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border {{ $item->kategori_badge['bg'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $item->kategori_badge['dot'] }}"></span>
                                    {{ $item->kategori_badge['text'] }}
                                </span>
                            </td>
                            <td class="py-3 px-5">
                                @if($item->status_aktif)
                                    <span class="inline-block bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs px-2.5 py-0.5 rounded-full font-medium">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-block bg-gray-100 text-gray-600 border border-gray-200 text-xs px-2.5 py-0.5 rounded-full font-medium">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-5 text-xs text-gray-500 max-w-xs truncate">
                                {{ $item->keterangan ?? '-' }}
                            </td>
                            <td class="py-3 px-5 text-right font-medium">
                                <a href="{{ route('kandang.show', $item->id) }}" class="text-[#00875a] hover:underline mr-2 text-xs">Detail</a>
                                @if(auth()->user()->isPengelola())
                                    <a href="{{ route('kandang.edit', $item->id) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-400 text-xs">
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
