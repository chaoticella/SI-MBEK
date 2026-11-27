{{-- Dropdown pilih kambing (dikelompokkan per kandang) --}}
<select name="{{ $name ?? 'id_kambing' }}" id="{{ $id ?? 'id_kambing' }}"
        class="w-full rounded-lg border-stone-300 bg-white px-3 py-2 text-sm shadow-sm ring-1 ring-stone-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    <option value="">{{ $placeholder ?? '— Pilih kambing —' }}</option>
    @foreach ($daftarKambing->groupBy(fn ($k) => $k->kandang?->label ?? 'Tanpa kandang') as $kandang => $list)
        <optgroup label="{{ $kandang }}">
            @foreach ($list as $k)
                <option value="{{ $k->id_kambing }}" @selected((string) $selected === (string) $k->id_kambing)>
                    {{ $k->label }}
                </option>
            @endforeach
        </optgroup>
    @endforeach
</select>
