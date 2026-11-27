<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKambingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ubah ke true (atau sesuaikan logika otorisasi) agar request tidak ditolak dengan 403
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_kambing' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kambings', 'kode_kambing')->ignore($this->route('kambing')),
            ],
        ];
    }
}