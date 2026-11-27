<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreKambingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_kambing'  => 'required|string|max:50|unique:kambings,kode_kambing',
            'kandang_id'    => 'required|exists:kandangs,id',
            'ras'           => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:jantan,betina',
            'tanggal_lahir' => 'nullable|date|before_or_equal:today',
            'status'        => 'required|in:indukan,anakan,siap_jual',
        ];
    }

    /**
     * Pesan error dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'kode_kambing.required'        => 'Kode kambing wajib diisi.',
            'kode_kambing.unique'          => 'Kode kambing sudah dipakai.',
            'kandang_id.required'          => 'Kandang wajib dipilih.',
            'kandang_id.exists'            => 'Kandang yang dipilih tidak ditemukan.',
            'ras.required'                 => 'Jenis/ras wajib diisi.',
            'jenis_kelamin.required'       => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in'             => 'Jenis kelamin harus jantan atau betina.',
            'tanggal_lahir.date'           => 'Tanggal lahir tidak valid.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',
            'status.required'              => 'Status wajib dipilih.',
            'status.in'                    => 'Status harus indukan, anakan, atau siap_jual.',
        ];
    }
}