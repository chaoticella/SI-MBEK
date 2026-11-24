@extends('layouts.app')
@section('title', 'Pencatatan Penyakit')

@php
    $inp = 'rounded-lg bg-white px-3 py-2 text-sm ring-1 ring-stone-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none';
    $bolehHapus = ! auth()->check() || (auth()->user()->role ?? 'pemilik') === 'pemilik';
@endphp

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-1">
    <div>
        <div class="flex items-center gap-2.5">
            <span class="text-2xl">🩺</span>
            <h1 class="text-2xl font-black text-stone-900 tracking-tight">Pencatatan Penyakit</h1>
        </div>
        <p class="text-xs sm:text-sm text-stone-500 mt-1">Riwayat penyakit, gejala, obat/vaksin, dan hasil akhir tiap kambing.</p>
    </div>
    <a href="{{ route('penyakit.create') }}" class="rounded-xl bg-[#0D4231] hover:bg-[#082E22] px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-xs transition-all">+ Catat Penyakit</a>
</div>

{{-- Kartu ringkasan --}}
<div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
    @foreach ([
        ['Sedang sakit', $ringkasan['sakit'], 'text-amber-600', ['status' => 'sakit']],
        ['Di kandang isolasi', $ringkasan['isolasi'], 'text-red-600', ['status' => 'sakit']],
        ['Sembuh bulan ini', $ringkasan['sembuh'], 'text-emerald-600', ['status' => 'sembuh']],
        ['Mati bulan ini', $ringkasan['mati'], 'text-stone-800', ['status' => 'mati']],
    ] as [$label, $n, $c, $q])
        <a href="{{ route('penyakit.index', $q) }}" class="rounded-2xl border border-stone-200 bg-white p-4 shadow-sm hover:border-emerald-300">
            <p class="text-xs text-stone-500">{{ $label }}</p>
            <p class="text-3xl font-bold {{ $c }}">{{ $n }}</p>
        </a>
    @endforeach
</div>

<div class="flex flex-wrap items-center justify-between gap-3">
    <p class="text-sm text-stone-500">Riwayat penyakit, gejala, obat/vaksin, dan hasil akhir tiap kambing.</p>
    <a href="{{ route('penyakit.create') }}" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-red-700">+ Catat Penyakit</a>
</div>

<form method="GET" class="grid gap-3 rounded-2xl border border-stone-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-6">
    <input type="search" name="q" value="{{ $filter['q'] ?? '' }}" placeholder="Cari gejala / diagnosa…" class="{{ $inp }} lg:col-span-2">
    <div class="lg:col-span-2">
        @include('kesehatan.partials.select-kambing', ['name' => 'kambing', 'id' => 'f-kambing',
            'selected' => $filter['kambing'] ?? '', 'placeholder' => 'Semua kambing'])
    </div>
    <select name="status" class="{{ $inp }}">
        <option value="">Semua status</option>
        @foreach (\App\Models\Penyakit::STATUS as $v => $l)
            <option value="{{ $v }}" @selected(($filter['status'] ?? '') === $v)>{{ $l }}</option>
        @endforeach
    </select>
    <button class="rounded-lg bg-stone-800 px-4 py-2 text-sm font-medium text-white">Filter</button>
    <input type="date" name="dari" value="{{ $filter['dari'] ?? '' }}" class="{{ $inp }}" title="Sakit dari tanggal">
    <input type="date" name="sampai" value="{{ $filter['sampai'] ?? '' }}" class="{{ $inp }}" title="Sakit sampai tanggal">
    @if (array_filter($filter))
        <a href="{{ route('penyakit.index') }}" class="self-center text-sm text-emerald-700 hover:underline">Reset filter</a>
    @endif
</form>

<div class="overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-stone-100 text-left text-xs uppercase tracking-wide text-stone-500">
                <tr>
                    <th class="px-4 py-3">Tgl Sakit</th>
                    <th class="px-4 py-3">Kambing</th>
                    <th class="px-4 py-3">Diagnosa &amp; Gejala</th>
                    <th class="px-4 py-3">Obat / Vaksin</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
            @forelse ($data as $p)
                @php $isolasiAktif = $p->status === 'sakit' && $p->diIsolasi(); @endphp
                <tr class="{{ $isolasiAktif ? 'border-l-4 border-l-red-500 bg-red-50/50' : ($p->status === 'sakit' ? 'border-l-4 border-l-amber-400' : '') }} hover:bg-stone-50">
                    <td class="whitespace-nowrap px-4 py-3">
                        {{ $p->tanggal_sakit->translatedFormat('d M Y') }}
                        <p class="text-xs text-stone-400">{{ $p->user?->name ?? '—' }}</p>
                    </td>
                    <td class="whitespace-nowrap px-4 py-3">@include('kesehatan.partials.kambing-badge', ['kambing' => $p->kambing])</td>
                    <td class="max-w-xs px-4 py-3">
                        <p class="font-medium">{{ $p->diagnosa ?: 'Belum didiagnosa' }}</p>
                        <p class="text-xs text-stone-500">{{ \Illuminate\Support\Str::limit($p->gejala, 80) }}</p>
                    </td>
                    <td class="px-4 py-3">
                        @forelse (collect($p->jenis_obat_vaksin)->take(3) as $o)
                            <span class="mb-1 inline-block whitespace-nowrap rounded bg-sky-50 px-1.5 py-0.5 text-[11px] text-sky-800 ring-1 ring-sky-200">
                                {{ ['obat' => '💊', 'vaksin' => '💉', 'vitamin' => '🧪'][$o['jenis']] ?? '' }} {{ $o['nama'] }}
                            </span>
                        @empty
                            <span class="text-xs text-stone-400">—</span>
                        @endforelse
                        @if (count($p->jenis_obat_vaksin ?? []) > 3)
                            <span class="text-xs text-stone-500">+{{ count($p->jenis_obat_vaksin) - 3 }} lagi</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-4 py-3">
                        @include('kesehatan.penyakit._status-badge')
                        <p class="mt-1 text-xs text-stone-500">
                            @if ($p->status === 'sakit') hari ke-{{ $p->lama_sakit + 1 }}
                            @else {{ $p->lama_sakit }} hari @endif
                        </p>
                    </td>
                    <td class="whitespace-nowrap px-4 py-3 text-right">
                        <a href="{{ route('penyakit.show', $p) }}" class="text-stone-500 hover:text-stone-800">Detail</a>
                        <a href="{{ route('penyakit.edit', $p) }}" class="ml-3 text-emerald-700 hover:underline">Edit</a>
                        @if ($p->status === 'sakit')
                            <form method="POST" action="{{ route('penyakit.sembuh', $p) }}" class="js-confirm inline"
                                  data-confirm="Tandai {{ $p->kambing->kode }} sembuh hari ini?">
                                @csrf @method('PATCH')
                                <button class="ml-3 text-emerald-600 hover:underline">✓ Sembuh</button>
                            </form>
                        @endif
                        @if ($bolehHapus)
                            <form method="POST" action="{{ route('penyakit.destroy', $p) }}" class="js-confirm inline"
                                  data-confirm="Hapus pencatatan penyakit {{ $p->kambing->kode }}? Data tidak bisa dikembalikan.">
                                @csrf @method('DELETE')
                                <button class="ml-3 text-red-600 hover:underline">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-stone-400">Belum ada pencatatan penyakit.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($data->hasPages())
        <div class="border-t border-stone-100 px-4 py-3">{{ $data->links() }}</div>
    @endif
</div>
@endsection
