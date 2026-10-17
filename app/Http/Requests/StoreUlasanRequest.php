<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUlasanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'pelanggan';
    }

    public function rules(): array
    {
        return [
            'rating'   => ['required', 'integer', 'between:1,5'],
            'komentar' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Rating wajib diisi.',
            'rating.integer'  => 'Rating harus berupa angka.',
            'rating.between'  => 'Rating harus antara 1 sampai 5.',
            'komentar.max'    => 'Komentar maksimal 2000 karakter.',
        ];
    }
}
