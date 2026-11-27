@extends('layouts.app')

@section('title', '403 - Akses Ditolak')

@section('content')
<div class="max-w-xl mx-auto my-12 text-center bg-white rounded-3xl border border-[#ECE5DA] shadow-2xs p-8 sm:p-12">
    <div class="w-16 h-16 bg-red-50 text-red-600 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-4 border border-red-200">
        <i class="fa-solid fa-shield-halved"></i>
    </div>

    <h2 class="text-2xl font-black text-stone-900 tracking-tight">403 &bull; Akses Dibatasi</h2>
    <p class="text-xs sm:text-sm text-stone-600 mt-2 leading-relaxed">
        {{ $message ?? 'Maaf, akun Anda dengan peran (' . (auth()->user()->role_label ?? 'Pegawai') . ') tidak memiliki wewenang untuk membuka halaman ini.' }}
    </p>

    <div class="p-4 rounded-2xl bg-[#FAF7F2] border border-[#ECE5DA] text-xs text-stone-700 text-left my-6 space-y-1">
        <p class="font-bold flex items-center gap-1.5 text-stone-900">
            <i class="fa-solid fa-circle-info text-[#6B9080]"></i>
            <span>Informasi Hak Akses (Middleware CheckRole):</span>
        </p>
        <p>&bull; <strong>Pengelola:</strong> Memiliki akses penuh termasuk pengelolaan pengguna &amp; manipulasi struktur kandang.</p>
        <p>&bull; <strong>Pegawai:</strong> Memiliki hak akses operasional (melihat data kandang, pencatatan kesehatan &amp; kambing).</p>
    </div>

    <div class="flex items-center justify-center gap-3">
        <a href="{{ url()->previous() }}" 
           class="px-5 py-2.5 rounded-xl border border-[#ECE5DA] text-xs sm:text-sm font-semibold text-stone-700 hover:bg-[#FAF7F2] transition-colors">
            Kembali
        </a>
        <a href="{{ route('dashboard') }}" 
           class="px-5 py-2.5 rounded-xl bg-[#0D4231] hover:bg-[#082E22] text-white text-xs sm:text-sm font-bold shadow-xs transition-colors">
            Menuju Dashboard
        </a>
    </div>
</div>
@endsection
