<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'pelanggan';
    }

    public function rules(): array
    {
        return [
            'paket_layanan_id' => ['required', 'exists:paket_layanan,id'],
            'alamat'           => ['required', 'string', 'max:1000'],
            'tanggal'          => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai'        => ['required', 'date_format:H:i'],
            'catatan'          => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'paket_layanan_id.required' => 'Paket layanan wajib dipilih.',
            'paket_layanan_id.exists'   => 'Paket layanan tidak ditemukan.',
            'alamat.required'           => 'Alamat wajib diisi.',
            'alamat.max'                => 'Alamat maksimal 1000 karakter.',
            'tanggal.required'          => 'Tanggal wajib diisi.',
            'tanggal.after_or_equal'    => 'Tanggal tidak boleh sebelum hari ini.',
            'jam_mulai.required'        => 'Jam mulai wajib diisi.',
            'jam_mulai.date_format'     => 'Format jam mulai harus HH:MM.',
            'catatan.max'               => 'Catatan maksimal 2000 karakter.',
        ];
    }
}
