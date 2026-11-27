@extends('layouts.app')
@section('title', 'Detail Kondisi Fisik')

@php $selisih = $kondisi->selisihBerat(); $maxBerat = max(1, (float) $riwayat->max('berat_badan')); @endphp

@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-3">
                @include('kesehatan.partials.kambing-badge', ['kambing' => $kondisi->kambing])
                <p class="text-sm text-stone-500">Dicek {{ $kondisi->tanggal_cek->translatedFormat('l, d F Y') }}
                    oleh {{ $kondisi->user?->name ?? '—' }}</p>
            </div>

            <dl class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-4">
                @foreach ([
                    ['Berat', $kondisi->berat_badan . ' kg', false, $selisih !== null ? (($selisih >= 0 ? '+' : '') . $selisih . ' kg') : null],
                    ['Tinggi', $kondisi->tinggi_badan ? $kondisi->tinggi_badan . ' cm' : '—', false, null],
                    ['Suhu', $kondisi->suhu_tubuh . ' °C', $kondisi->suhuAbnormal(), null],
                    ['Nafsu makan', ucfirst($kondisi->nafsu_makan), $kondisi->nafsu_makan === 'buruk', null],
                    ['BCS', $kondisi->bcs ?? '—', $kondisi->bcsAbnormal(), null],
                    ['FAMACHA', $kondisi->famacha ?? '—', $kondisi->famachaBahaya(), null],
                ] as [$label, $nilai, $bahaya, $extra])
                    <div class="rounded-xl p-3 {{ $bahaya ? 'bg-red-50 ring-1 ring-red-200' : 'bg-stone-50' }}">
                        <dt class="text-xs text-stone-500">{{ $label }}</dt>
                        <dd class="text-lg font-bold {{ $bahaya ? 'text-red-600' : '' }}">{{ $nilai }}</dd>
                        @if ($extra)
                            <dd class="text-xs {{ $selisih < 0 ? 'text-red-600' : 'text-emerald-600' }}">{{ $extra }} dari cek sebelumnya</dd>
                        @endif
                    </div>
                @endforeach
            </dl>

            @if ($kondisi->catatan)
                <p class="mt-4 rounded-lg bg-stone-50 p-3 text-sm"><b>Catatan:</b> {{ $kondisi->catatan }}</p>
            @endif
        </div>

        {{-- Grafik batang perkembangan berat (CSS murni) --}}
        <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
            <h3 class="font-semibold">Perkembangan Berat Badan</h3>
            <div class="mt-4 flex h-48 items-end gap-2">
                @foreach ($riwayat as $r)
                    <a href="{{ route('kondisi-fisik.show', $r) }}" class="group flex flex-1 flex-col items-center gap-1"
                       title="{{ $r->tanggal_cek->format('d/m/Y') }}: {{ $r->berat_badan }} kg">
                        <span class="text-[10px] text-stone-500">{{ (float) $r->berat_badan }}</span>
                        <span class="w-full rounded-t-md {{ $r->is($kondisi) ? 'bg-emerald-600' : 'bg-emerald-200 group-hover:bg-emerald-400' }}"
                              style="height: {{ max(4, ($r->berat_badan / $maxBerat) * 150) }}px"></span>
                        <span class="text-[10px] text-stone-400">{{ $r->tanggal_cek->format('d/m') }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <aside class="space-y-3">
        <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-semibold">Hasil pemeriksaan</h3>
            @forelse ($kondisi->peringatan() as $w)
                <p class="mt-2 rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700">⚠ {{ $w }}</p>
            @empty
                <p class="mt-2 rounded-lg bg-emerald-50 px-3 py-2 text-sm text-emerald-700">✓ Semua parameter normal</p>
            @endforelse
        </div>
        <a href="{{ route('kondisi-fisik.edit', $kondisi) }}" class="block rounded-lg bg-emerald-600 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-emerald-700">Edit data</a>
        <a href="{{ route('kondisi-fisik.create', ['kambing' => $kondisi->id_kambing]) }}" class="block rounded-lg border border-stone-300 px-4 py-2.5 text-center text-sm hover:bg-stone-50">Catat kondisi baru kambing ini</a>
        <a href="{{ route('penyakit.create', ['kambing' => $kondisi->id_kambing]) }}" class="block rounded-lg border border-red-300 px-4 py-2.5 text-center text-sm text-red-700 hover:bg-red-50">Buat pencatatan penyakit</a>
        <a href="{{ route('kondisi-fisik.index', ['kambing' => $kondisi->id_kambing]) }}" class="block py-2 text-center text-sm text-stone-500 hover:underline">Semua riwayat kambing ini</a>
    </aside>
</div>
@endsection
