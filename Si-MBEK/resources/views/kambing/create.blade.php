@extends('layouts.app')

@section('title', 'Tambah Kambing Baru')
@section('page_title', 'Tambah Kambing Baru')
@section('page_subtitle', 'Catat identitas, ras, status, dan penempatan kandang kambing')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <div class="flex items-center justify-between pb-5 border-b border-gray-100 mb-6">
            <div>
                <h3 class="text-base font-bold text-gray-800">Formulir Data Kambing</h3>
                <p class="text-xs text-gray-500 mt-0.5">Isi data identitas kambing dengan lengkap</p>
            </div>
            <a href="{{ route('kambing.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i>
                <span>Kembali</span>
            </a>
        </div>

        <form action="{{ route('kambing.store') }}" method="POST" class="space-y-5">
            @csrf
            @include('kambing._form', ['kambing' => null, 'submitLabel' => 'Simpan Kambing'])
        </form>
    </div>
</div>
@endsection