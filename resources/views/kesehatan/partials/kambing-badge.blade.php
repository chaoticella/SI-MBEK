{{-- Marker visual kambing: kode + kandang + tanda sakit/isolasi --}}
@php
    $sakit   = in_array($kambing->id_kambing, $idKambingSakit ?? []);
    $isolasi = $kambing->kandang?->kategori === 'isolasi';
@endphp
<div class="flex items-center gap-2">
    @if ($sakit && $isolasi)
        <span class="relative flex h-2.5 w-2.5" title="Sakit · di kandang isolasi">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-red-600"></span>
        </span>
    @elseif ($sakit)
        <span class="h-2.5 w-2.5 rounded-full bg-amber-500" title="Sakit · belum diisolasi"></span>
    @else
        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500" title="Sehat"></span>
    @endif
    <div class="leading-tight">
        <p class="font-semibold">{{ $kambing->kode }}</p>
        <p class="text-xs text-stone-500">
            {{ $kambing->jenis_ras }}
            @if ($kambing->kandang) · Blok {{ $kambing->kandang->blok_kandang }} @endif
            @if ($isolasi)
                <span class="ml-1 rounded bg-red-100 px-1.5 py-px text-[10px] font-bold uppercase text-red-700">Isolasi</span>
            @endif
        </p>
    </div>
</div>
