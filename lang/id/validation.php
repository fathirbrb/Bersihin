<?php

return [
    'after_or_equal'   => ':Attribute tidak boleh sebelum :date.',
    'between'          => [
        'numeric' => ':Attribute harus antara :min sampai :max.',
    ],
    'boolean'          => ':Attribute harus bernilai ya atau tidak.',
    'confirmed'        => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Kata sandi sekarang tidak cocok.',
    'date'             => ':Attribute bukan tanggal yang valid.',
    'date_format'      => 'Format :attribute tidak sesuai.',
    'email'            => 'Format email tidak valid.',
    'exists'           => ':Attribute tidak ditemukan.',
    'image'            => ':Attribute harus berupa gambar.',
    'in'               => 'Pilihan :attribute tidak valid.',
    'integer'          => ':Attribute harus berupa bilangan bulat.',
    'max'              => [
        'file'    => 'Ukuran :attribute maksimal :max KB.',
        'numeric' => ':Attribute maksimal :max.',
        'string'  => ':Attribute maksimal :max karakter.',
    ],
    'mimes'            => ':Attribute harus berformat :values.',
    'min'              => [
        'numeric' => ':Attribute minimal :min.',
        'string'  => ':Attribute minimal :min karakter.',
    ],
    'numeric'          => ':Attribute harus berupa angka.',
    'required'         => ':Attribute wajib diisi.',
    'required_if'      => ':Attribute wajib diisi.',
    'string'           => ':Attribute harus berupa teks.',
    'unique'           => ':Attribute sudah terpakai.',
    'uploaded'         => ':Attribute gagal diunggah.',

    'custom' => [
        'email' => [
            'unique' => 'Email sudah terdaftar.',
        ],
        'foto' => [
            'max'   => 'Ukuran foto maksimal 2 MB.',
            'mimes' => 'Foto harus berformat JPG atau PNG.',
        ],
    ],

    'attributes' => [
        'alamat'              => 'alamat',
        'bukti_bayar'         => 'bukti pembayaran',
        'catatan'             => 'catatan',
        'current_password'    => 'kata sandi sekarang',
        'deskripsi'           => 'deskripsi',
        'email'               => 'email',
        'estimasi_durasi_jam' => 'durasi',
        'foto'                => 'foto',
        'harga'               => 'harga',
        'jam_mulai'           => 'jam mulai',
        'komentar'            => 'komentar',
        'metode'              => 'metode pembayaran',
        'nama'                => 'nama',
        'nama_paket'          => 'nama paket',
        'no_hp'               => 'nomor HP',
        'paket_layanan_id'    => 'paket layanan',
        'password'            => 'kata sandi',
        'rating'              => 'rating',
        'role'                => 'peran',
        'status'              => 'status',
        'status_verifikasi'   => 'status verifikasi',
        'tanggal'             => 'tanggal',
    ],
];
