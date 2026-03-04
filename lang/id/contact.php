<?php

return [
    'title' => 'Kontak Kami',
    'subtitle' => 'Hubungi kami untuk informasi akademik, kegiatan, atau layanan program studi.',
    'form_error_heading' => 'Periksa kembali data formulir Anda.',
    'form_title' => 'Kirim Pesan',
    'form_description' => 'Isi formulir berikut. Tim kami akan merespons melalui email atau telepon yang Anda cantumkan.',
    'required_note' => 'Kolom bertanda * wajib diisi.',
    'submit_button' => 'Kirim Pesan',
    'social_media' => 'Media Sosial',
    'info_title' => 'Informasi Kontak',
    'fields' => [
        'name' => 'Nama *',
        'email' => 'Email *',
        'phone' => 'Telepon',
        'subject' => 'Subjek *',
        'message' => 'Pesan *',
    ],
    'placeholders' => [
        'name' => 'Nama lengkap',
        'email' => 'nama@email.com',
        'phone' => '08xxxxxxxxxx',
        'subject' => 'Contoh: Pertanyaan pendaftaran',
        'message' => 'Tulis pesan Anda di sini...',
    ],
    'social' => [
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'x' => 'X',
        'linkedin' => 'LinkedIn',
    ],
    'info' => [
        'email_label' => 'Email',
        'phone_label' => 'Telepon',
    ],
    'success_message' => 'Pesan Anda berhasil dikirim. Tim kami akan segera menghubungi Anda.',
    'validation' => [
        'name' => [
            'required' => 'Nama wajib diisi.',
            'max' => 'Nama maksimal 120 karakter.',
        ],
        'email' => [
            'required' => 'Email wajib diisi.',
            'email' => 'Format email tidak valid.',
            'max' => 'Email maksimal 255 karakter.',
        ],
        'phone' => [
            'max' => 'Nomor telepon maksimal 30 karakter.',
        ],
        'subject' => [
            'required' => 'Subjek wajib diisi.',
            'max' => 'Subjek maksimal 150 karakter.',
        ],
        'message' => [
            'required' => 'Pesan wajib diisi.',
            'min' => 'Pesan minimal 10 karakter.',
            'max' => 'Pesan maksimal 2000 karakter.',
        ],
    ],
    'attributes' => [
        'name' => 'nama',
        'email' => 'email',
        'phone' => 'nomor telepon',
        'subject' => 'subjek',
        'message' => 'pesan',
    ],
];
