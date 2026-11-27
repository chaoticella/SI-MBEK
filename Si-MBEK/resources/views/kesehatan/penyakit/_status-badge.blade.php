@php
    $cls = ['sakit' => 'bg-amber-100 text-amber-800', 'sembuh' => 'bg-emerald-100 text-emerald-700', 'mati' => 'bg-stone-800 text-white'][$p->status];
@endphp
<span class="inline-block whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $cls }}">
    {{ \App\Models\Penyakit::STATUS[$p->status] }}
</span>
