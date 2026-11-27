@php
    $inp = 'w-full rounded-lg bg-white px-3 py-2 text-sm shadow-sm ring-1 ring-stone-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none';
    $lbl = 'mb-1 block text-sm font-medium text-stone-700';
    $K = \App\Models\KondisiFisik::class;
@endphp

@if ($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="font-semibold">Periksa kembali isian berikut:</p>
        <ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div id="form-kondisi" class="grid gap-6 lg:grid-cols-3"
     data-url-terakhir="{{ url('kondisi-fisik/terakhir') }}"
     data-suhu-min="{{ $K::SUHU_MIN }}" data-suhu-max="{{ $K::SUHU_MAX }}"
     data-bcs-min="{{ $K::BCS_MIN }}"   data-bcs-max="{{ $K::BCS_MAX }}"
     data-famacha-bahaya="{{ $K::FAMACHA_BAHAYA }}">

    <div class="space-y-5 rounded-2xl border border-stone-200 bg-white p-5 shadow-sm lg:col-span-2">
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="id_kambing" class="{{ $lbl }}">Kambing <span class="text-red-500">*</span></label>
                @include('kesehatan.partials.select-kambing', ['selected' => old('id_kambing', $kondisi->id_kambing)])
                {{-- diisi jQuery: info kandang & pencatatan terakhir --}}
                <div id="info-kambing" class="mt-2 hidden rounded-lg bg-stone-50 p-3 text-xs text-stone-600"></div>
            </div>

            <div>
                <label for="tanggal_cek" class="{{ $lbl }}">Tanggal Cek <span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_cek" name="tanggal_cek" max="{{ now()->toDateString() }}"
                       value="{{ old('tanggal_cek', $kondisi->tanggal_cek?->format('Y-m-d') ?? $kondisi->tanggal_cek) }}" class="{{ $inp }}" required>
            </div>

            <div>
                <span class="{{ $lbl }}">Nafsu Makan <span class="text-red-500">*</span></span>
                <div class="grid grid-cols-3 gap-2">
                    @foreach ($K::NAFSU_MAKAN as $val => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="nafsu_makan" value="{{ $val }}" class="peer sr-only"
                                   @checked(old('nafsu_makan', $kondisi->nafsu_makan) === $val)>
                            <span class="block rounded-lg border border-stone-300 py-2 text-center text-sm peer-checked:border-transparent
                                {{ ['baik' => 'peer-checked:bg-emerald-600', 'sedang' => 'peer-checked:bg-amber-500', 'buruk' => 'peer-checked:bg-red-600'][$val] }} peer-checked:text-white">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <fieldset class="grid gap-4 border-t border-stone-100 pt-5 sm:grid-cols-2">
            <legend class="mb-2 text-sm font-semibold text-stone-500">Ukuran Tubuh</legend>
            <div>
                <label for="berat_badan" class="{{ $lbl }}">Berat Badan (kg) <span class="text-red-500">*</span></label>
                <input type="text" inputmode="decimal" id="berat_badan" name="berat_badan" placeholder="mis. 32,5"
                       value="{{ old('berat_badan', $kondisi->berat_badan) }}" class="{{ $inp }}" required>
                <p id="hint-berat" class="mt-1 text-xs text-stone-500"></p>
            </div>
            <div>
                <label for="tinggi_badan" class="{{ $lbl }}">Tinggi Badan (cm)</label>
                <input type="text" inputmode="decimal" id="tinggi_badan" name="tinggi_badan" placeholder="tinggi pundak"
                       value="{{ old('tinggi_badan', $kondisi->tinggi_badan) }}" class="{{ $inp }}">
            </div>
        </fieldset>

        <fieldset class="grid gap-4 border-t border-stone-100 pt-5 sm:grid-cols-2">
            <legend class="mb-2 text-sm font-semibold text-stone-500">Tanda Vital</legend>
            <div>
                <label for="suhu_tubuh" class="{{ $lbl }}">Suhu Tubuh (°C) <span class="text-red-500">*</span></label>
                <input type="text" inputmode="decimal" id="suhu_tubuh" name="suhu_tubuh" placeholder="normal {{ $K::SUHU_MIN }}–{{ $K::SUHU_MAX }}"
                       value="{{ old('suhu_tubuh', $kondisi->suhu_tubuh) }}" class="{{ $inp }} js-vital" data-vital="suhu" required>
                <p class="js-vital-hint mt-1 text-xs" data-for="suhu"></p>
            </div>
            <div>
                <label for="bcs" class="{{ $lbl }}">BCS (1–5)</label>
                <select id="bcs" name="bcs" class="{{ $inp }} js-vital" data-vital="bcs">
                    <option value="">—</option>
                    @foreach ([1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5] as $b)
                        <option value="{{ $b }}" @selected((string) old('bcs', $kondisi->bcs !== null ? (float) $kondisi->bcs : '') === (string) $b)>
                            {{ number_format($b, 1) }} {{ ['1'=>'— sangat kurus','3'=>'— ideal','5'=>'— obesitas'][(string)$b] ?? '' }}
                        </option>
                    @endforeach
                </select>
                <p class="js-vital-hint mt-1 text-xs" data-for="bcs"></p>
            </div>
            <div>
                <span class="{{ $lbl }}">FAMACHA (warna selaput mata)</span>
                <div class="flex gap-1.5">
                    @foreach ([1 => '#c0392b', 2 => '#e06666', 3 => '#f4a6a6', 4 => '#f8d0d0', 5 => '#fdf2f2'] as $f => $warna)
                        <label class="flex-1 cursor-pointer" title="Skor {{ $f }}">
                            <input type="radio" name="famacha" value="{{ $f }}" class="peer sr-only js-vital" data-vital="famacha"
                                   @checked((string) old('famacha', $kondisi->famacha) === (string) $f)>
                            <span class="block rounded-lg border-2 border-transparent py-2 text-center text-sm font-bold ring-1 ring-stone-300 peer-checked:border-stone-800"
                                  style="background: {{ $warna }}; color: {{ $f <= 2 ? '#fff' : '#7f1d1d' }}">{{ $f }}</span>
                        </label>
                    @endforeach
                </div>
                <p class="js-vital-hint mt-1 text-xs" data-for="famacha"></p>
            </div>
        </fieldset>

        <div class="border-t border-stone-100 pt-5">
            <label for="catatan" class="{{ $lbl }}">Catatan</label>
            <textarea id="catatan" name="catatan" rows="2" class="{{ $inp }}" placeholder="opsional">{{ old('catatan', $kondisi->catatan) }}</textarea>
        </div>
    </div>

    {{-- Panel kanan: ringkasan peringatan (diisi jQuery secara live) --}}
    <aside class="space-y-4">
        <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm lg:sticky lg:top-20">
            <h3 class="text-sm font-semibold">Pemeriksaan cepat</h3>
            <ul id="ringkasan-vital" class="mt-3 space-y-2 text-sm">
                <li class="text-stone-400">Isi tanda vital untuk melihat status.</li>
            </ul>
            <div id="saran-pencatatan-penyakit" class="mt-4 hidden rounded-lg bg-red-50 p-3 text-xs text-red-700">
                Ada tanda tidak normal. Setelah menyimpan, pertimbangkan membuat
                <b>pencatatan penyakit</b> untuk kambing ini.
            </div>
            <div class="mt-5 flex flex-col gap-2">
                <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                    Simpan
                </button>
                @unless ($kondisi->exists)
                    <button type="submit" name="lanjut" value="1"
                            class="rounded-lg border border-emerald-600 px-4 py-2.5 text-sm font-semibold text-emerald-700 hover:bg-emerald-50">
                        Simpan &amp; input kambing berikutnya
                    </button>
                @endunless
                <a href="{{ route('kondisi-fisik.index') }}" class="py-2 text-center text-sm text-stone-500 hover:underline">Batal</a>
            </div>
        </div>
    </aside>
</div>
