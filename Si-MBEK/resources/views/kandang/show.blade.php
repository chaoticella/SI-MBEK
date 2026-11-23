@extends('layouts.app')

@section('title', 'Detail Kandang ' . $kandang->blok_kandang)
@section('page_title', 'Detail Kandang')
@section('page_subtitle', 'Informasi lengkap ' . $kandang->blok_kandang)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <!-- Header -->
        <div class="flex items-start justify-between pb-6 border-b border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl {{ $kandang->kategori === 'isolasi' ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-emerald-50 text-emerald-600 border border-emerald-200' }} flex items-center justify-center text-2xl font-bold">
                    <i class="fa-solid fa-warehouse"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h3 class="text-xl font-extrabold text-gray-900">{{ $kandang->blok_kandang }}</h3>
                        @if($kandang->kategori === 'isolasi')
                            <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-0.5 rounded uppercase">
                                Isolasi / Sakit
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Terdaftar sejak {{ $kandang->created_at ? $kandang->created_at->translatedFormat('d F Y') : '-' }}
                    </p>
                </div>
            </div>

            <a href="{{ route('kandang.index') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i>
                <span>Kembali</span>
            </a>
        </div>

        <!-- Detail Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-gray-100 text-sm">
            <div>
                <p class="text-xs font-bold uppercase text-gray-400">Kapasitas Maksimal</p>
                <p class="text-2xl font-extrabold text-gray-800 mt-1">
                    {{ $kandang->kapasitas_maksimal }} <span class="text-sm font-normal text-gray-500">Ekor Kambing</span>
                </p>
            </div>

            <div>
                <p class="text-xs font-bold uppercase text-gray-400">Kategori Kandang</p>
                <div class="mt-1.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $kandang->kategori_badge['bg'] }}">
                        <span class="w-2 h-2 rounded-full {{ $kandang->kategori_badge['dot'] }}"></span>
                        {{ $kandang->kategori_label }}
                    </span>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold uppercase text-gray-400">Status Operasional</p>
                <div class="mt-1.5">
                    @if($kandang->status_aktif)
                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs px-3 py-1 rounded-full font-semibold">
                            <i class="fa-solid fa-circle-check text-xs"></i>
                            <span>Aktif (Siap Digunakan)</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-600 border border-gray-200 text-xs px-3 py-1 rounded-full font-semibold">
                            <i class="fa-solid fa-circle-minus text-xs"></i>
                            <span>Nonaktif (Dalam Perbaikan/Kosong)</span>
                        </span>
                    @endif
                </div>
            </div>

            <div>
                <p class="text-xs font-bold uppercase text-gray-400">Pembaruan Terakhir</p>
                <p class="text-sm text-gray-700 font-medium mt-1">
                    {{ $kandang->updated_at ? $kandang->updated_at->diffForHumans() : '-' }}
                </p>
            </div>
        </div>

        <!-- Description Note -->
        <div class="pt-6">
            <p class="text-xs font-bold uppercase text-gray-400">Keterangan & Catatan Lokasi</p>
            <div class="mt-2 p-4 rounded-2xl bg-gray-50/70 border border-gray-100 text-xs sm:text-sm text-gray-700 leading-relaxed">
                {{ $kandang->keterangan ?: 'Tidak ada keterangan khusus untuk blok kandang ini.' }}
            </div>
        </div>

        <!-- Actions -->
        @if(auth()->user()->isPengelola())
            <div class="pt-6 mt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                <form action="{{ route('kandang.toggle-status', $kandang->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-semibold {{ $kandang->status_aktif ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} transition-colors">
                        {{ $kandang->status_aktif ? 'Nonaktifkan Kandang' : 'Aktifkan Kandang' }}
                    </button>
                </form>

                <a href="{{ route('kandang.edit', $kandang->id) }}" 
                   class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    Edit Data
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
