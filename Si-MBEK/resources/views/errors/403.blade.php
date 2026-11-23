@extends('layouts.app')

@section('title', '403 - Akses Ditolak')
@section('page_title', 'Akses Dibatasi')
@section('page_subtitle', 'Anda tidak memiliki hak akses yang memadai')

@section('content')
<div class="max-w-xl mx-auto my-12 text-center bg-white rounded-3xl border border-gray-100 shadow-sm p-8 sm:p-12">
    <div class="w-20 h-20 bg-red-50 text-red-600 rounded-3xl flex items-center justify-center text-4xl mx-auto mb-5 shadow-xs border border-red-200">
        <i class="fa-solid fa-shield-halved"></i>
    </div>

    <h2 class="text-2xl font-black text-gray-900 tracking-tight">403 - Akses Ditolak</h2>
    <p class="text-sm text-gray-600 mt-2 leading-relaxed">
        {{ $message ?? 'Maaf, akun Anda dengan peran (' . (auth()->user()->role_label ?? 'Pegawai') . ') tidak memiliki izin untuk membuka halaman ini.' }}
    </p>

    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-800 text-left my-6 space-y-1">
        <p class="font-bold flex items-center gap-1.5 text-amber-900">
            <i class="fa-solid fa-circle-info"></i>
            <span>Informasi Hak Akses (Middleware CheckRole):</span>
        </p>
        <p>&bull; <strong>Pengelola:</strong> Memiliki akses penuh termasuk pengelolaan akun &amp; manipulasi kandang.</p>
        <p>&bull; <strong>Pegawai:</strong> Memiliki hak akses operasional (melihat kandang, input data kesehatan/kambing).</p>
    </div>

    <div class="flex items-center justify-center gap-3">
        <a href="{{ url()->previous() }}" 
           class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
            Kembali ke Halaman Sebelumnya
        </a>
        <a href="{{ route('dashboard') }}" 
           class="px-5 py-2.5 rounded-xl bg-[#00875a] hover:bg-[#00734c] text-white text-xs sm:text-sm font-bold shadow-sm transition-colors">
            Menuju Dashboard
        </a>
    </div>
</div>
@endsection
