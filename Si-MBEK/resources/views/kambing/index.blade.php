@extends('layouts.app')

@section('title', 'Data Kambing')
@section('page_title', 'Data Kambing')
@section('page_subtitle', 'Identitas utama kambing, ras, status ternak, dan penempatan kandang')

@section('content')
<div class="space-y-6">
    <!-- Description & Action Button Row -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-xs sm:text-sm text-gray-600">
            Daftar seluruh kambing di peternakan beserta status dan kandang penempatannya.
        </p>

        @if(auth()->user()->isPengelola())
            <a href="{{ route('kambing.create') }}"
               class="inline-flex items-center justify-center gap-2 bg-[#00875a] hover:bg-[#00734c] text-white px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-sm transition-all flex-shrink-0">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Kambing</span>
            </a>
        @endif
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-gray-500 uppercase text-[11px] font-bold tracking-wider">
                        <th class="py-3.5 px-5">ID Kambing</th>
                        <th class="py-3.5 px-5">Jenis / Ras</th>
                        <th class="py-3.5 px-5">Jenis Kelamin</th>
                        <th class="py-3.5 px-5">Tanggal Lahir</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5">Kandang</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kambing as $item)
                        @php
                            $statusStyle = match ($item->status) {
                                'indukan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'anakan'  => 'bg-amber-50 text-amber-700 border-amber-200',
                                default   => 'bg-blue-50 text-blue-700 border-blue-200',
                            };
                            $statusLabel = ['indukan' => 'Indukan', 'anakan' => 'Anakan', 'siap_jual' => 'Siap Jual'][$item->status] ?? $item->status;
                            $isJantan = $item->jenis_kelamin === 'jantan';
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="py-3.5 px-5 font-bold text-gray-800">{{ $item->kode_kambing }}</td>

                            <td class="py-3.5 px-5 text-gray-700">{{ $item->ras }}</td>

                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $isJantan ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-pink-50 text-pink-700 border-pink-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $isJantan ? 'bg-blue-500' : 'bg-pink-500' }}"></span>
                                    {{ $isJantan ? 'Jantan' : 'Betina' }}
                                </span>
                            </td>

                            <td class="py-3.5 px-5 text-gray-600">
                                {{ $item->tanggal_lahir ? $item->tanggal_lahir->format('d M Y') : '-' }}
                            </td>

                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusStyle }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <td class="py-3.5 px-5 text-gray-700">
                                {{ $item->kandang->blok_kandang ?? '-' }}
                            </td>

                            <td class="py-3.5 px-5 text-right font-medium">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('kambing.show', $item->id_kambing) }}"
                                       class="text-gray-600 hover:text-gray-900 text-xs font-semibold hover:underline">
                                        Detail
                                    </a>

                                    @if(auth()->user()->isPengelola())
                                        <a href="{{ route('kambing.edit', $item->id_kambing) }}"
                                           class="text-blue-600 hover:text-blue-800 text-xs font-semibold hover:underline">
                                            Edit
                                        </a>

                                        <form action="{{ route('kambing.destroy', $item->id_kambing) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Hapus kambing {{ $item->kode_kambing }} dari sistem?')"
                                                    class="text-red-500 hover:text-red-700 text-xs font-semibold hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400 text-xs">
                                <i class="fa-solid fa-paw text-4xl mb-2 text-gray-300"></i>
                                <p class="text-sm font-semibold text-gray-500">Belum ada data kambing</p>
                                <p class="text-xs text-gray-400 mt-1">Klik "Tambah Kambing" untuk mulai mencatat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kambing->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $kambing->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
