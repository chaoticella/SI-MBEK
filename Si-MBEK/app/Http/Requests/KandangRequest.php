<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KandangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Handled by role middleware
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $kandangId = $this->route('kandang') ? $this->route('kandang')->id ?? $this->route('kandang') : null;

        return [
            'blok_kandang' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kandangs', 'blok_kandang')->ignore($kandangId),
            ],
            'kapasitas_maksimal' => ['required', 'integer', 'min:1', 'max:1000'],
            'kategori' => ['required', Rule::in(['jantan', 'betina', 'isolasi'])],
            'status_aktif' => ['nullable', 'boolean'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Custom Indonesian validation messages.
     */
    public function messages(): array
    {
        return [
            'blok_kandang.required' => 'Nama/Nomor blok kandang wajib diisi.',
            'blok_kandang.max' => 'Nama blok kandang maksimal 50 karakter.',
            'blok_kandang.unique' => 'Blok kandang ini sudah terdaftar di sistem.',
            'kapasitas_maksimal.required' => 'Kapasitas maksimal kandang wajib diisi.',
            'kapasitas_maksimal.integer' => 'Kapasitas maksimal harus berupa bilangan bulat.',
            'kapasitas_maksimal.min' => 'Kapasitas maksimal minimal 1 ekor.',
            'kapasitas_maksimal.max' => 'Kapasitas maksimal tidak boleh melebihi 1000 ekor.',
            'kategori.required' => 'Kategori kandang wajib dipilih.',
            'kategori.in' => 'Kategori kandang harus dipilih antara Jantan, Betina, atau Isolasi/Sakit.',
            'keterangan.max' => 'Keterangan kandang tidak boleh lebih dari 500 karakter.',
        ];
    }

    /**
     * Prepare data before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('status_aktif')) {
            $this->merge([
                'status_aktif' => filter_var($this->status_aktif, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
