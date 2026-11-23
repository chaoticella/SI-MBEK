@extends('layouts.app')

@section('title', $title ?? 'Modul SI-MBEK')
@section('page_title', $title ?? 'Sistem Informasi Peternakan')
@section('page_subtitle', $moduleName ?? 'Fitur SI-MBEK')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb / Header card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-[#00875a] border border-emerald-200 flex items-center justify-center text-2xl font-bold flex-shrink-0">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
                <span class="inline-block bg-amber-100 text-amber-800 text-[11px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider mb-1">
                    {{ $moduleName ?? 'Modul Lainnya' }}
                </span>
                <h3 class="text-xl font-black text-gray-900">{{ $title }}</h3>
                <p class="text-xs sm:text-sm text-gray-600 mt-1 max-w-2xl leading-relaxed">
                    {{ $description ?? 'Halaman ini merupakan bagian dari pembagian kelompok proyek SI-MBEK.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-3xl border border-gray-200/80 p-6 sm:p-8 shadow-xs text-center">
        <div class="max-w-md mx-auto space-y-4">
            <div class="w-12 h-12 rounded-full bg-emerald-100 text-[#00875a] flex items-center justify-center mx-auto text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h4 class="font-bold text-gray-800 text-base">Modul 1 (Manajemen Pengguna &amp; Kandang) Siap Digunakan</h4>
            <p class="text-xs text-gray-500 leading-relaxed">
                Anda dapat menguji fungsionalitas Modul 1 secara menyeluruh melalui menu <strong>Data Kandang</strong> dan <strong>Manajemen Pengguna</strong>.
            </p>
            <div class="pt-2 flex justify-center gap-3">
                <a href="{{ route('kandang.index') }}" class="px-4 py-2 bg-[#00875a] text-white rounded-xl text-xs font-bold hover:bg-[#00734c] shadow-xs">
                    Buka Data Kandang
                </a>
                @if(auth()->user()->isPengelola())
                    <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-xs font-bold hover:bg-black shadow-xs">
                        Buka Kelola Pengguna
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
