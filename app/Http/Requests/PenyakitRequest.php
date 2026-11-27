<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PenyakitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // pemilik & pegawai boleh input/ubah data penyakit
    }

    public function rules(): array
    {
        return [
            'id_kambing'    => ['required', 'integer', Rule::exists('kambing', 'id_kambing')],
            'tanggal_sakit' => ['required', 'date', 'before_or_equal:today'],
            'diagnosa'      => ['nullable', 'string', 'max:100'],
            'gejala'        => ['required', 'string', 'min:3', 'max:2000'],

            // status akhir dipilih lewat radio (dinamis jQuery)
            'status_akhir'     => ['required', Rule::in(['sakit', 'sembuh', 'mati'])],
            'tanggal_sembuh'   => ['nullable', 'required_if:status_akhir,sembuh', 'date',
                                   'after_or_equal:tanggal_sakit', 'before_or_equal:today'],
            'tanggal_kematian' => ['nullable', 'required_if:status_akhir,mati', 'date',
                                   'after_or_equal:tanggal_sakit', 'before_or_equal:today'],

            // daftar obat/vaksin dinamis (baris ditambah/hapus pakai jQuery)
            'obat'           => ['nullable', 'array', 'max:20'],
            'obat.*.jenis'   => ['required', Rule::in(['obat', 'vaksin', 'vitamin'])],
            'obat.*.nama'    => ['required', 'string', 'max:100'],
            'obat.*.dosis'   => ['nullable', 'string', 'max:50'],
            'obat.*.tanggal' => ['nullable', 'date', 'after_or_equal:tanggal_sakit', 'before_or_equal:today'],

            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'id_kambing'       => 'kambing',
            'tanggal_sakit'    => 'tanggal sakit',
            'tanggal_sembuh'   => 'tanggal sembuh',
            'tanggal_kematian' => 'tanggal kematian',
            'status_akhir'     => 'status',
            'obat.*.jenis'     => 'jenis obat/vaksin',
            'obat.*.nama'      => 'nama obat/vaksin',
            'obat.*.dosis'     => 'dosis',
            'obat.*.tanggal'   => 'tanggal pemberian',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_sembuh.required_if'   => 'Tanggal sembuh wajib diisi jika status Sembuh.',
            'tanggal_kematian.required_if' => 'Tanggal kematian wajib diisi jika status Mati.',
        ];
    }

    /** Buang baris obat yang benar-benar kosong sebelum divalidasi. */
    protected function prepareForValidation(): void
    {
        $obat = collect($this->input('obat', []))
            ->filter(fn ($o) => filled($o['nama'] ?? null) || filled($o['dosis'] ?? null))
            ->values()
            ->all();

        $this->merge(['obat' => $obat]);
    }

    /** Data siap simpan ke tabel penyakit. */
    public function dataPenyakit(): array
    {
        $v = $this->validated();
        $status = $v['status_akhir'];

        return [
            'id_kambing'        => $v['id_kambing'],
            'tanggal_sakit'     => $v['tanggal_sakit'],
            'diagnosa'          => $v['diagnosa'] ?? null,
            'gejala'            => $v['gejala'],
            'jenis_obat_vaksin' => array_values($v['obat'] ?? []) ?: null,
            'tanggal_sembuh'    => $status === 'sembuh' ? $v['tanggal_sembuh'] : null,
            'tanggal_kematian'  => $status === 'mati' ? $v['tanggal_kematian'] : null,
            'catatan'           => $v['catatan'] ?? null,
        ];
    }
}
