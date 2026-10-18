<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePembayaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'pelanggan';
    }

    public function rules(): array
    {
        return [
            'metode'      => ['required', 'in:transfer,tunai'],
            'bukti_bayar' => ['nullable', 'required_if:metode,transfer', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'metode.required'            => 'Metode pembayaran wajib dipilih.',
            'metode.in'                  => 'Metode pembayaran harus transfer atau tunai.',
            'bukti_bayar.required_if'    => 'Bukti pembayaran wajib diunggah untuk metode transfer.',
            'bukti_bayar.image'          => 'Bukti pembayaran harus berupa gambar.',
            'bukti_bayar.mimes'          => 'Bukti pembayaran harus berformat JPEG, PNG, atau JPG.',
            'bukti_bayar.max'            => 'Ukuran bukti pembayaran maksimal 2 MB.',
        ];
    }
}
