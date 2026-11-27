@extends('layouts.app')

@section('title', 'Detail Kandang: ' . $kandang->blok_kandang)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header Halaman Langsung di Konten -->
    <div class="flex items-center justify-between pb-1">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xl">🐐</span>
                <h1 class="text-xl font-black text-stone-900 tracking-tight">Detail Blok Kandang</h1>
            </div>
            <p class="text-xs text-stone-500 mt-0.5">Informasi kapasitas, kategori, dan kambing penghuni</p>
        </div>

        <a href="{{ route('kandang.index') }}" 
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-[#ECE5DA] bg-white text-xs font-semibold text-stone-600 hover:bg-[#FAF7F2] transition-colors shadow-2xs">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Card Putih dengan Rounded Corners -->
    <div class="bg-white rounded-3xl border border-[#ECE5DA] shadow-2xs p-6 sm:p-8">
        <!-- Title & Icon -->
        <div class="flex items-start justify-between pb-6 border-b border-[#ECE5DA]">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl {{ $kandang->kategori === 'isolasi' ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-[#FAF7F2] text-[#0D4231] border border-[#ECE5DA]' }} flex items-center justify-center text-2xl font-bold">
                    <i class="fa-solid fa-warehouse"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h3 class="text-xl font-black text-stone-900">{{ $kandang->blok_kandang }}</h3>
                        @if($kandang->kategori === 'isolasi')
                            <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-0.5 rounded uppercase">
                                Isolasi / Sakit
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-stone-500 mt-1">
                        Terdaftar sejak {{ $kandang->created_at ? $kandang->created_at->translatedFormat('d F Y') : '-' }}
                    </p>
                </div>
            </div>

            @if(auth()->user()->isPengelola())
                <a href="{{ route('kandang.edit', $kandang->id) }}" 
                   class="px-4 py-2 rounded-xl bg-[#0D4231] hover:bg-[#082E22] text-white text-xs font-bold transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                    <span>Edit Kandang</span>
                </a>
            @endif
        </div>

        <!-- Detail Grid Minimalis -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-[#ECE5DA] text-sm">
            <div>
                <p class="text-xs font-bold uppercase text-stone-400">Kapasitas Maksimal</p>
                <p class="text-2xl font-black text-stone-900 mt-1">
                    {{ $kandang->kapasitas_maksimal }} <span class="text-sm font-normal text-stone-500">Ekor Kambing</span>
                </p>
            </div>

            <div>
                <p class="text-xs font-bold uppercase text-stone-400">Kategori Kandang</p>
                <div class="mt-1.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $kandang->kategori_badge['bg'] }}">
                        <span class="w-2 h-2 rounded-full {{ $kandang->kategori_badge['dot'] }}"></span>
                        {{ $kandang->kategori_label }}
                    </span>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold uppercase text-stone-400">Status Operasional</p>
                <div class="mt-1.5">
                    @if($kandang->status_aktif)
                        <span class="inline-flex items-center gap-1.5 bg-[#E8F3EE] text-[#0D4231] border border-[#C5E2D4] text-xs px-3 py-1 rounded-full font-semibold">
                            <i class="fa-solid fa-circle-check text-xs"></i>
                            <span>Aktif (Siap Digunakan)</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-stone-100 text-stone-600 border border-stone-200 text-xs px-3 py-1 rounded-full font-semibold">
                            <i class="fa-solid fa-circle-minus text-xs"></i>
                            <span>Nonaktif</span>
                        </span>
                    @endif
                </div>
            </div>

            <div>
                <p class="text-xs font-bold uppercase text-stone-400">Pembaruan Terakhir</p>
                <p class="text-sm text-stone-700 font-medium mt-1">
                    {{ $kandang->updated_at ? $kandang->updated_at->diffForHumans() : '-' }}
                </p>
            </div>
        </div>

        <!-- Description Note -->
        <div class="pt-6">
            <p class="text-xs font-bold uppercase text-stone-400">Keterangan & Catatan Lokasi</p>
            <div class="mt-2 p-4 rounded-2xl bg-[#FAF7F2] border border-[#ECE5DA] text-xs sm:text-sm text-stone-700 leading-relaxed">
                {{ $kandang->keterangan ?: 'Tidak ada keterangan khusus untuk blok kandang ini.' }}
            </div>
        </div>

        <!-- Daftar Kambing yang Menghuni Kandang ini (Jika ada dari Modul 3/Kesehatan) -->
        @if($kandang->kambing && $kandang->kambing->count() > 0)
        <div class="pt-6 mt-6 border-t border-[#ECE5DA]">
            <p class="text-xs font-bold uppercase text-stone-400 mb-3">Kambing Penghuni Blok Ini ({{ $kandang->kambing->count() }} ekor)</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                @foreach($kandang->kambing as $kmb)
                    <div class="p-2.5 rounded-xl bg-[#FAF7F2] border border-[#ECE5DA] text-xs flex items-center justify-between">
                        <div>
                            <span class="font-bold text-stone-900">{{ $kmb->kode }}</span>
                            <span class="block text-[10px] text-stone-500">{{ $kmb->jenis_ras }}</span>
                        </div>
                        <span class="text-[10px] font-semibold text-stone-400 capitalize">{{ $kmb->jenis_kelamin }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
