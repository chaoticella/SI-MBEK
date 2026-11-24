@php
    $inp = 'w-full rounded-lg bg-white px-3 py-2 text-sm shadow-sm ring-1 ring-stone-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none';
    $lbl = 'mb-1 block text-sm font-medium text-stone-700';
    $obatLama = old('obat', $penyakit->jenis_obat_vaksin ?? []);
    $status   = old('status_akhir', $penyakit->exists ? $penyakit->status : 'sakit');
    $tgl      = fn ($v) => $v instanceof \Carbon\CarbonInterface ? $v->format('Y-m-d') : $v;
    $presetObat = [
        ['obat', 'Ivermectin', '1 ml/50 kg'], ['obat', 'Oxytetracycline LA', '1 ml/10 kg'],
        ['obat', 'Albendazole', '10 mg/kg'], ['vitamin', 'Vitamin B Kompleks', '2 ml'],
        ['obat', 'Gusanex (spray luka)', 'secukupnya'], ['vaksin', 'Vaksin PMK', '2 ml'],
        ['vaksin', 'Vaksin Anthrax', '1 ml'], ['vaksin', 'Vaksin Orf', '1 dosis'],
    ];
@endphp

@if ($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="font-semibold">Periksa kembali isian berikut:</p>
        <ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div id="form-penyakit" class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        {{-- Identitas kasus --}}
        <section class="grid gap-4 rounded-2xl border border-stone-200 bg-white p-5 shadow-sm sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="id_kambing" class="{{ $lbl }}">Kambing <span class="text-red-500">*</span></label>
                @include('kesehatan.partials.select-kambing', ['selected' => old('id_kambing', $penyakit->id_kambing)])
            </div>
            <div>
                <label for="tanggal_sakit" class="{{ $lbl }}">Tanggal Mulai Sakit <span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_sakit" name="tanggal_sakit" max="{{ now()->toDateString() }}"
                       value="{{ old('tanggal_sakit', $tgl($penyakit->tanggal_sakit)) }}" class="{{ $inp }}" required>
            </div>
            <div>
                <label for="diagnosa" class="{{ $lbl }}">Diagnosa / Nama Penyakit</label>
                <input type="text" id="diagnosa" name="diagnosa" list="daftar-diagnosa" placeholder="mis. Scabies, Kembung, Orf"
                       value="{{ old('diagnosa', $penyakit->diagnosa) }}" class="{{ $inp }}">
                <datalist id="daftar-diagnosa">
                    @foreach (['Kembung (Bloat)', 'Scabies (Kudis)', 'Orf (Dakangan)', 'Cacingan', 'Diare', 'Pink Eye', 'Pneumonia', 'Mastitis', 'Foot Rot (Busuk Kuku)', 'PMK'] as $d)
                        <option value="{{ $d }}">
                    @endforeach
                </datalist>
            </div>
            <div class="sm:col-span-2">
                <label for="gejala" class="{{ $lbl }}">Gejala <span class="text-red-500">*</span></label>
                <textarea id="gejala" name="gejala" rows="3" class="{{ $inp }}" required
                          placeholder="mis. lemas, tidak mau makan, perut kembung sebelah kiri">{{ old('gejala', $penyakit->gejala) }}</textarea>
                {{-- Chip gejala cepat (jQuery menambahkan ke textarea) --}}
                <div class="mt-2 flex flex-wrap gap-1.5">
                    @foreach (['lemas', 'tidak mau makan', 'demam', 'diare', 'batuk', 'pilek', 'mata berair', 'pincang', 'kembung', 'gatal/kerontokan bulu', 'luka di mulut', 'kurus'] as $g)
                        <button type="button" class="js-chip-gejala rounded-full border border-stone-300 px-2.5 py-0.5 text-xs text-stone-600 hover:border-emerald-500 hover:bg-emerald-50"
                                data-gejala="{{ $g }}">+ {{ $g }}</button>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Obat / vaksin dinamis --}}
        <section class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div>
                    <h3 class="font-semibold">Obat / Vaksin yang Diberikan</h3>
                    <p class="text-xs text-stone-500">Tambah baris sebanyak obat yang diberikan. Klik preset untuk isi cepat.</p>
                </div>
                <button type="button" id="btn-tambah-obat"
                        class="rounded-lg bg-emerald-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-emerald-700">+ Tambah baris</button>
            </div>

            <div class="mt-3 flex flex-wrap gap-1.5">
                @foreach ($presetObat as [$j, $n, $d])
                    <button type="button" class="js-preset-obat rounded-full bg-stone-100 px-2.5 py-0.5 text-xs hover:bg-emerald-100"
                            data-jenis="{{ $j }}" data-nama="{{ $n }}" data-dosis="{{ $d }}">{{ $n }}</button>
                @endforeach
            </div>
            <datalist id="daftar-obat">
                @foreach ($presetObat as [, $n]) <option value="{{ $n }}"> @endforeach
            </datalist>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-[560px] text-sm">
                    <thead class="text-left text-xs uppercase text-stone-500">
                        <tr><th class="w-32 pb-1">Jenis</th><th class="pb-1">Nama</th><th class="w-32 pb-1">Dosis</th><th class="w-40 pb-1">Tgl diberikan</th><th class="w-8"></th></tr>
                    </thead>
                    <tbody id="obat-body">
                        @foreach (array_values($obatLama) as $i => $o)
                            @include('kesehatan.penyakit._obat-row', ['i' => $i, 'o' => $o])
                        @endforeach
                    </tbody>
                </table>
                <p id="obat-kosong" class="{{ count($obatLama) ? 'hidden' : '' }} rounded-lg border border-dashed border-stone-300 py-6 text-center text-sm text-stone-400">
                    Belum ada obat/vaksin. Klik “+ Tambah baris”.
                </p>
            </div>
            <p class="mt-2 text-right text-xs text-stone-500">Total: <b id="obat-total">{{ count($obatLama) }}</b> item</p>

            {{-- Template baris untuk di-clone jQuery --}}
            <template id="tpl-obat">
                @include('kesehatan.penyakit._obat-row', ['i' => '__INDEX__', 'o' => []])
            </template>
        </section>

        <section class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
            <label for="catatan" class="{{ $lbl }}">Catatan tambahan</label>
            <textarea id="catatan" name="catatan" rows="2" class="{{ $inp }}" placeholder="opsional — mis. sudah dipindah ke kandang isolasi">{{ old('catatan', $penyakit->catatan) }}</textarea>
        </section>
    </div>

    {{-- Status akhir --}}
    <aside>
        <div class="space-y-4 rounded-2xl border border-stone-200 bg-white p-5 shadow-sm lg:sticky lg:top-20">
            <h3 class="text-sm font-semibold">Status Kasus</h3>
            <div class="grid gap-2">
                @foreach (['sakit' => ['Masih sakit / dirawat', 'peer-checked:bg-amber-500'],
                           'sembuh' => ['Sudah sembuh', 'peer-checked:bg-emerald-600'],
                           'mati' => ['Mati', 'peer-checked:bg-stone-800']] as $v => [$l, $cls])
                    <label class="cursor-pointer">
                        <input type="radio" name="status_akhir" value="{{ $v }}" class="peer sr-only js-status" @checked($status === $v)>
                        <span class="block rounded-lg border border-stone-300 px-3 py-2 text-sm peer-checked:border-transparent peer-checked:text-white {{ $cls }}">{{ $l }}</span>
                    </label>
                @endforeach
            </div>

            <div class="js-status-field" data-status="sembuh">
                <label for="tanggal_sembuh" class="{{ $lbl }}">Tanggal Sembuh <span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_sembuh" name="tanggal_sembuh" max="{{ now()->toDateString() }}"
                       value="{{ old('tanggal_sembuh', $tgl($penyakit->tanggal_sembuh)) }}" class="{{ $inp }}">
            </div>
            <div class="js-status-field" data-status="mati">
                <label for="tanggal_kematian" class="{{ $lbl }}">Tanggal Kematian <span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_kematian" name="tanggal_kematian" max="{{ now()->toDateString() }}"
                       value="{{ old('tanggal_kematian', $tgl($penyakit->tanggal_kematian)) }}" class="{{ $inp }}">
                <p class="mt-1 text-xs text-stone-500">Kambing akan otomatis dinonaktifkan dari data populasi.</p>
            </div>
            <p id="info-lama-sakit" class="text-xs text-stone-500"></p>

            <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">Simpan Pencatatan Penyakit</button>
            <a href="{{ $penyakit->exists ? route('penyakit.show', $penyakit) : route('penyakit.index') }}"
               class="block text-center text-sm text-stone-500 hover:underline">Batal</a>
        </div>
    </aside>
</div>
