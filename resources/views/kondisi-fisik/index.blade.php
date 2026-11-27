@extends('layouts.app')
@section('title', 'Kondisi Fisik Kambing')

@php
    $inp =
        'rounded-lg bg-white px-3 py-2 text-sm ring-1 ring-stone-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none';
    $bolehHapus = !auth()->check() || (auth()->user()->role ?? 'pemilik') === 'pemilik';
@endphp

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-stone-500">Pencatatan berat, tinggi, nafsu makan, suhu, BCS & FAMACHA secara berkala.</p>
        <a href="{{ route('kondisi-fisik.create') }}"
            class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-700">+ Catat
            Kondisi</a>
    </div>

    {{-- Filter --}}
    <form method="GET"
        class="grid gap-3 rounded-2xl border border-stone-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-5">
        <div class="lg:col-span-2">
            @include('kesehatan.partials.select-kambing', [
                'name' => 'kambing',
                'id' => 'f-kambing',
                'selected' => $filter['kambing'] ?? '',
                'placeholder' => 'Semua kambing',
            ])
        </div>
        <input type="date" name="dari" value="{{ $filter['dari'] ?? '' }}" class="{{ $inp }}"
            title="Dari tanggal">
        <input type="date" name="sampai" value="{{ $filter['sampai'] ?? '' }}" class="{{ $inp }}"
            title="Sampai tanggal">
        <div class="flex gap-2">
            <select name="nafsu" class="{{ $inp }} flex-1">
                <option value="">Nafsu makan</option>
                @foreach (\App\Models\KondisiFisik::NAFSU_MAKAN as $v => $l)
                    <option value="{{ $v }}" @selected(($filter['nafsu'] ?? '') === $v)>{{ $l }}</option>
                @endforeach
            </select>
            <button class="rounded-lg bg-stone-800 px-4 text-sm font-medium text-white">Filter</button>
        </div>
        @if (array_filter($filter))
            <a href="{{ route('kondisi-fisik.index') }}"
                class="text-sm text-emerald-700 hover:underline sm:col-span-2 lg:col-span-5">Reset filter</a>
        @endif
    </form>

    <div class="overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-stone-100 text-left text-xs uppercase tracking-wide text-stone-500">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Kambing</th>
                        <th class="px-4 py-3 text-right">Berat</th>
                        <th class="px-4 py-3 text-right">Tinggi</th>
                        <th class="px-4 py-3 text-right">Suhu</th>
                        <th class="px-4 py-3">Nafsu</th>
                        <th class="px-4 py-3 text-center">BCS</th>
                        <th class="px-4 py-3 text-center">FAMACHA</th>
                        <th class="px-4 py-3">Kondisi</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($data as $row)
                        @php $warn = $row->peringatan(); @endphp
                        <tr class="{{ $warn ? 'bg-red-50/40' : '' }} hover:bg-stone-50">
                            <td class="whitespace-nowrap px-4 py-3">
                                {{ $row->tanggal_cek->translatedFormat('d M Y') }}
                                <p class="text-xs text-stone-400">{{ $row->user?->name ?? '—' }}</p>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">@include('kesehatan.partials.kambing-badge', ['kambing' => $row->kambing])</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right font-semibold">
                                {{ rtrim(rtrim(number_format($row->berat_badan, 2, ',', '.'), '0'), ',') }} kg</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right">
                                {{ $row->tinggi_badan ? $row->tinggi_badan . ' cm' : '—' }}</td>
                            <td
                                class="whitespace-nowrap px-4 py-3 text-right {{ $row->suhuAbnormal() ? 'font-bold text-red-600' : '' }}">
                                {{ $row->suhu_tubuh }}°C</td>
                            <td class="px-4 py-3">
                                <span
                                    class="rounded-full px-2 py-0.5 text-xs font-medium
                            {{ ['baik' => 'bg-emerald-100 text-emerald-700', 'sedang' => 'bg-amber-100 text-amber-700', 'buruk' => 'bg-red-100 text-red-700'][$row->nafsu_makan] }}">
                                    {{ ucfirst($row->nafsu_makan) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center {{ $row->bcsAbnormal() ? 'font-bold text-amber-600' : '' }}">
                                {{ $row->bcs ?? '—' }}</td>
                            <td class="px-4 py-3 text-center {{ $row->famachaBahaya() ? 'font-bold text-red-600' : '' }}">
                                {{ $row->famacha ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @forelse ($warn as $w)
                                    <span
                                        class="mb-1 inline-block whitespace-nowrap rounded bg-red-100 px-1.5 py-0.5 text-[11px] font-medium text-red-700">{{ $w }}</span>
                                @empty
                                    <span class="text-xs text-emerald-600">Normal</span>
                                @endforelse
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right">
                                <a href="{{ route('kondisi-fisik.show', $row) }}"
                                    class="text-stone-500 hover:text-stone-800">Detail</a>
                                <a href="{{ route('kondisi-fisik.edit', $row) }}"
                                    class="ml-3 text-emerald-700 hover:underline">Edit</a>
                                @if ($bolehHapus)
                                    <form method="POST" action="{{ route('kondisi-fisik.destroy', $row) }}"
                                        class="js-confirm inline"
                                        data-confirm="Hapus data kondisi {{ $row->kambing->kode }} tanggal {{ $row->tanggal_cek->format('d/m/Y') }}?">
                                        @csrf @method('DELETE')
                                        <button class="ml-3 text-red-600 hover:underline">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-12 text-center text-stone-400">Belum ada data kondisi fisik.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($data->hasPages())
            <div class="border-t border-stone-100 px-4 py-3">{{ $data->links() }}</div>
        @endif
    </div>
@endsection
