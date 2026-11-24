@extends('layouts.app')
@section('title', 'Detail Pencatatan Penyakit')

@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <div class="rounded-2xl border bg-white p-5 shadow-sm {{ $penyakit->status === 'sakit' && $penyakit->diIsolasi() ? 'border-red-300' : 'border-stone-200' }}">
            <div class="flex flex-wrap items-start justify-between gap-3">
                @include('kesehatan.partials.kambing-badge', ['kambing' => $penyakit->kambing])
                @include('kesehatan.penyakit._status-badge', ['p' => $penyakit])
            </div>

            <h2 class="mt-5 text-xl font-bold">{{ $penyakit->diagnosa ?: 'Belum didiagnosa' }}</h2>
            <p class="mt-1 whitespace-pre-line text-sm text-stone-700">{{ $penyakit->gejala }}</p>

            <dl class="mt-5 grid grid-cols-2 gap-3 text-sm sm:grid-cols-4">
                <div class="rounded-xl bg-stone-50 p-3"><dt class="text-xs text-stone-500">Mulai sakit</dt><dd class="font-semibold">{{ $penyakit->tanggal_sakit->translatedFormat('d M Y') }}</dd></div>
                <div class="rounded-xl bg-stone-50 p-3"><dt class="text-xs text-stone-500">Sembuh</dt><dd class="font-semibold">{{ $penyakit->tanggal_sembuh?->translatedFormat('d M Y') ?? '—' }}</dd></div>
                <div class="rounded-xl bg-stone-50 p-3"><dt class="text-xs text-stone-500">Kematian</dt><dd class="font-semibold">{{ $penyakit->tanggal_kematian?->translatedFormat('d M Y') ?? '—' }}</dd></div>
                <div class="rounded-xl bg-stone-50 p-3"><dt class="text-xs text-stone-500">Lama sakit</dt><dd class="font-semibold">{{ $penyakit->lama_sakit }} hari</dd></div>
            </dl>

            @if ($penyakit->status === 'sakit' && ! $penyakit->diIsolasi())
                <p class="mt-4 rounded-lg bg-amber-50 p-3 text-sm text-amber-800">⚠ Kambing ini sedang sakit tetapi belum berada di kandang isolasi.</p>
            @endif
            @if ($penyakit->catatan)
                <p class="mt-4 rounded-lg bg-stone-50 p-3 text-sm"><b>Catatan:</b> {{ $penyakit->catatan }}</p>
            @endif
            <p class="mt-4 text-xs text-stone-400">Dicatat oleh {{ $penyakit->user?->name ?? '—' }} · {{ $penyakit->created_at->diffForHumans() }}</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
            <h3 class="font-semibold">Riwayat Pengobatan</h3>
            <ol class="mt-4 space-y-3 border-l-2 border-sky-200 pl-5">
                @forelse (collect($penyakit->jenis_obat_vaksin)->sortBy('tanggal') as $o)
                    <li class="relative">
                        <span class="absolute -left-[27px] top-1 h-3 w-3 rounded-full bg-sky-500 ring-4 ring-white"></span>
                        <p class="font-medium">{{ ['obat' => '💊', 'vaksin' => '💉', 'vitamin' => '🧪'][$o['jenis']] ?? '' }} {{ $o['nama'] }}
                            @if (! empty($o['dosis'])) <span class="text-sm font-normal text-stone-500">· {{ $o['dosis'] }}</span> @endif
                        </p>
                        <p class="text-xs text-stone-500">{{ ucfirst($o['jenis']) }} · {{ ! empty($o['tanggal']) ? \Carbon\Carbon::parse($o['tanggal'])->translatedFormat('d M Y') : 'tanggal tidak dicatat' }}</p>
                    </li>
                @empty
                    <li class="text-sm text-stone-400">Belum ada obat/vaksin yang dicatat.</li>
                @endforelse
            </ol>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
            <h3 class="font-semibold">Kondisi Fisik Terakhir</h3>
            <table class="mt-3 w-full text-sm">
                <thead class="text-left text-xs uppercase text-stone-500"><tr><th class="py-1">Tanggal</th><th>Berat</th><th>Suhu</th><th>Nafsu</th><th>FAMACHA</th></tr></thead>
                <tbody class="divide-y divide-stone-100">
                @forelse ($kondisiTerakhir as $k)
                    <tr>
                        <td class="py-2"><a href="{{ route('kondisi-fisik.show', $k) }}" class="text-emerald-700 hover:underline">{{ $k->tanggal_cek->format('d/m/Y') }}</a></td>
                        <td>{{ (float) $k->berat_badan }} kg</td>
                        <td class="{{ $k->suhuAbnormal() ? 'font-bold text-red-600' : '' }}">{{ $k->suhu_tubuh }}°C</td>
                        <td>{{ ucfirst($k->nafsu_makan) }}</td>
                        <td class="{{ $k->famachaBahaya() ? 'font-bold text-red-600' : '' }}">{{ $k->famacha ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-center text-stone-400">Belum ada data kondisi fisik.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <aside class="space-y-3">
        <a href="{{ route('penyakit.edit', $penyakit) }}" class="block rounded-lg bg-emerald-600 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-emerald-700">Edit / Update Status</a>
        @if ($penyakit->status === 'sakit')
            <form method="POST" action="{{ route('penyakit.sembuh', $penyakit) }}" class="js-confirm" data-confirm="Tandai sembuh hari ini?">
                @csrf @method('PATCH')
                <button class="w-full rounded-lg border border-emerald-600 px-4 py-2.5 text-sm font-semibold text-emerald-700 hover:bg-emerald-50">✓ Tandai Sembuh Hari Ini</button>
            </form>
        @endif
        <a href="{{ route('kondisi-fisik.create', ['kambing' => $penyakit->id_kambing]) }}" class="block rounded-lg border border-stone-300 px-4 py-2.5 text-center text-sm hover:bg-stone-50">Catat kondisi fisik</a>

        <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-semibold">Riwayat penyakit lain ({{ $riwayatLain->count() }})</h3>
            <ul class="mt-3 space-y-2 text-sm">
                @forelse ($riwayatLain as $r)
                    <li>
                        <a href="{{ route('penyakit.show', $r) }}" class="flex items-center justify-between gap-2 rounded-lg p-2 hover:bg-stone-50">
                            <span>{{ $r->diagnosa ?: \Illuminate\Support\Str::limit($r->gejala, 25) }}
                                <span class="block text-xs text-stone-500">{{ $r->tanggal_sakit->format('d/m/Y') }}</span></span>
                            @include('kesehatan.penyakit._status-badge', ['p' => $r])
                        </a>
                    </li>
                @empty
                    <li class="text-stone-400">Tidak ada riwayat lain.</li>
                @endforelse
            </ul>
        </div>
        <a href="{{ route('penyakit.index') }}" class="block py-2 text-center text-sm text-stone-500 hover:underline">← Kembali ke daftar</a>
    </aside>
</div>
@endsection
