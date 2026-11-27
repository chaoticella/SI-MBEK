@extends('layouts.app')

@section('title', $title ?? 'Fitur Peternakan')

@section('content')
<div class="space-y-6">
    <!-- Header Halaman Langsung di Konten -->
    <div class="bg-white rounded-3xl border border-[#ECE5DA] shadow-2xs p-6 sm:p-8">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#E8F3EE] text-[#0D4231] border border-[#C5E2D4] flex items-center justify-center text-xl font-bold flex-shrink-0">
                <span>🐐</span>
            </div>
            <div>
                <span class="inline-block bg-[#F3EDE2] text-[#8D5B4C] text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider mb-1">
                    {{ $moduleName ?? 'Modul Lainnya' }}
                </span>
                <h1 class="text-2xl font-black text-stone-900">{{ $title }}</h1>
                <p class="text-xs sm:text-sm text-stone-500 mt-1 max-w-2xl leading-relaxed">
                    {{ $description ?? 'Halaman ini merupakan bagian dari pembagian kelompok proyek SI-MBEK.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-white rounded-3xl border border-[#ECE5DA] p-6 sm:p-8 shadow-2xs text-center">
        <div class="max-w-md mx-auto space-y-4">
            <div class="w-12 h-12 rounded-full bg-[#E8F3EE] text-[#0D4231] flex items-center justify-center mx-auto text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h4 class="font-bold text-stone-900 text-base">Modul 1: Manajemen Pengguna &amp; Kandang Telah Aktif</h4>
            <p class="text-xs text-stone-500 leading-relaxed">
                Anda dapat menguji fitur Modul 1 secara lengkap melalui menu <strong>Data Kandang</strong> dan <strong>Manajemen Pengguna</strong>.
            </p>
            <div class="pt-2 flex justify-center gap-3">
                <a href="{{ route('kandang.index') }}" class="px-4 py-2.5 bg-[#0D4231] text-white rounded-xl text-xs font-bold hover:bg-[#082E22] shadow-xs">
                    Buka Data Kandang
                </a>
                @if(auth()->user()->isPengelola())
                    <a href="{{ route('users.index') }}" class="px-4 py-2.5 bg-[#2D2A26] text-white rounded-xl text-xs font-bold hover:bg-black shadow-xs">
                        Kelola Pengguna
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
