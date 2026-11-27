{{-- Satu baris obat/vaksin. $i = index (angka atau "__INDEX__" untuk template jQuery) --}}
@php $rc = 'w-full rounded-md bg-white px-2 py-1.5 text-sm ring-1 ring-stone-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none'; @endphp
<tr class="obat-row align-top">
    <td class="py-1.5 pr-2">
        <select name="obat[{{ $i }}][jenis]" class="{{ $rc }} js-obat-jenis">
            @foreach (['obat' => '💊 Obat', 'vaksin' => '💉 Vaksin', 'vitamin' => '🧪 Vitamin'] as $v => $l)
                <option value="{{ $v }}" @selected(($o['jenis'] ?? 'obat') === $v)>{{ $l }}</option>
            @endforeach
        </select>
    </td>
    <td class="py-1.5 pr-2">
        <input type="text" name="obat[{{ $i }}][nama]" value="{{ $o['nama'] ?? '' }}" placeholder="Nama obat/vaksin"
               class="{{ $rc }} js-obat-nama" list="daftar-obat">
    </td>
    <td class="py-1.5 pr-2">
        <input type="text" name="obat[{{ $i }}][dosis]" value="{{ $o['dosis'] ?? '' }}" placeholder="mis. 2 ml" class="{{ $rc }}">
    </td>
    <td class="py-1.5 pr-2">
        <input type="date" name="obat[{{ $i }}][tanggal]" value="{{ $o['tanggal'] ?? '' }}" class="{{ $rc }} js-obat-tanggal">
    </td>
    <td class="py-1.5 text-right">
        <button type="button" class="js-hapus-obat rounded-md px-2 py-1.5 text-red-500 hover:bg-red-50" title="Hapus baris">&times;</button>
    </td>
</tr>
