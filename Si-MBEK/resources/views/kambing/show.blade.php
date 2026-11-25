@extends('layouts.app')

@section('title', 'Detail Kambing')
@section('page_title', 'Detail Kambing ' . $kambing->kode_kambing)
@section('page_subtitle', 'Identitas kambing dan riwayat perkawinannya')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Identitas -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <div class="flex items-center justify-between pb-5 border-b border-gray-100 mb-6">
            <h3 class="text-base font-bold text-gray-800">Identitas Kambing</h3>
            <div class="flex items-center gap-2">
                @if(auth()->user()->isPengelola())
                    <a href="{{ route('kambing.edit', $kambing->id) }}"
                       class="px-3 py-1.5 rounded-xl bg-[#00875a] hover:bg-[#00734c] text-white text-xs font-semibold transition-colors">
                        Edit
                    </a>
                @endif
                <a href="{{ route('kambing.index') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm">
            <div>
                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">ID Kambing</dt>
                <dd class="mt-1 font-semibold text-gray-800">{{ $kambing->kode_kambing }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis / Ras</dt>
                <dd class="mt-1 text-gray-800">{{ $kambing->ras }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Kelamin</dt>
                <dd class="mt-1 text-gray-800 capitalize">{{ $kambing->jenis_kelamin }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Lahir</dt>
                <dd class="mt-1 text-gray-800">{{ $kambing->tanggal_lahir ? $kambing->tanggal_lahir->format('d M Y') : '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Status</dt>
                <dd class="mt-1 text-gray-800">{{ ['indukan' => 'Indukan', 'anakan' => 'Anakan', 'siap_jual' => 'Siap Jual'][$kambing->status] ?? $kambing->status }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Kandang</dt>
                <dd class="mt-1 text-gray-800">{{ $kambing->kandang->blok_kandang ?? '-' }}</dd>
            </div>
        </dl>
    </div>

    <!-- Riwayat Perkawinan -->
    @php
        $riwayat = $kambing->jenis_kelamin === 'jantan'
            ? $kambing->perkawinanSebagaiPejantan
            : $kambing->perkawinanSebagaiIndukan;
        $isJantan = $kambing->jenis_kelamin === 'jantan';
    @endphp

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-800">Riwayat Perkawinan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-gray-500 uppercase text-[11px] font-bold tracking-wider">
                        <th class="py-3 px-5">Tanggal Kawin</th>
                        <th class="py-3 px-5">{{ $isJantan ? 'Indukan' : 'Pejantan' }}</th>
                        <th class="py-3 px-5">Estimasi Melahirkan</th>
                        <th class="py-3 px-5">Jumlah Anak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayat as $p)
                        <tr>
                            <td class="py-3 px-5">{{ $p->tanggal_kawin->format('d M Y') }}</td>
                            <td class="py-3 px-5 font-semibold text-gray-800">
                                {{ $isJantan ? $p->indukan->kode_kambing : $p->pejantan->kode_kambing }}
                            </td>
                            <td class="py-3 px-5">{{ $p->estimasi_melahirkan ? $p->estimasi_melahirkan->format('d M Y') : '-' }}</td>
                            <td class="py-3 px-5">{{ $p->jumlah_anak_lahir }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400 text-xs">Belum ada riwayat perkawinan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection