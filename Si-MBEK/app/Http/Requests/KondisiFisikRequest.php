<?php

namespace App\Http\Requests;

use App\Models\KondisiFisik;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KondisiFisikRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // pemilik & pegawai boleh input/ubah kondisi fisik
    }

    public function rules(): array
    {
        $idKondisi = $this->route('kondisi')?->id_kondisi;

        return [
            'id_kambing'   => ['required', 'integer', Rule::exists('kambing', 'id_kambing')],
            'tanggal_cek'  => [
                'required', 'date', 'before_or_equal:today',
                // 1 kambing hanya boleh dicek 1x per tanggal
                function ($attr, $value, $fail) use ($idKondisi) {
                    $sudahAda = KondisiFisik::where('id_kambing', $this->input('id_kambing'))
                        ->whereDate('tanggal_cek', $value)
                        ->when($idKondisi, fn ($q) => $q->whereKeyNot($idKondisi))
                        ->exists();
                    if ($sudahAda) {
                        $fail('Kambing ini sudah dicek pada tanggal tersebut. Silakan edit data yang sudah ada.');
                    }
                },
            ],
            'berat_badan'  => ['required', 'numeric', 'min:0.5', 'max:200'],
            'tinggi_badan' => ['nullable', 'numeric', 'min:10', 'max:150'],
            'nafsu_makan'  => ['required', Rule::in(['baik', 'sedang', 'buruk'])],
            'suhu_tubuh'   => ['required', 'numeric', 'between:35,43'],
            'bcs'          => ['nullable', 'numeric', 'between:1,5'],
            'famacha'      => ['nullable', 'integer', 'between:1,5'],
            'catatan'      => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'id_kambing'   => 'kambing',
            'tanggal_cek'  => 'tanggal cek',
            'berat_badan'  => 'berat badan',
            'tinggi_badan' => 'tinggi badan',
            'nafsu_makan'  => 'nafsu makan',
            'suhu_tubuh'   => 'suhu tubuh',
            'bcs'          => 'BCS',
            'famacha'      => 'FAMACHA',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_cek.before_or_equal' => 'Tanggal cek tidak boleh melebihi hari ini.',
        ];
    }

    /** Ganti koma jadi titik (pegawai di HP sering ketik "38,9"). */
    protected function prepareForValidation(): void
    {
        $angka = ['berat_badan', 'tinggi_badan', 'suhu_tubuh', 'bcs'];
        $data = [];
        foreach ($angka as $f) {
            if ($this->filled($f)) {
                $data[$f] = str_replace(',', '.', $this->input($f));
            }
        }
        $this->merge($data);
    }
}
