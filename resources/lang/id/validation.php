<?php

return [
    'required' => ':attribute wajib diisi.',
    'email'    => ':attribute harus berupa alamat email yang valid.',
    'unique'   => ':attribute sudah digunakan.',
    'min'      => [
        'string' => ':attribute minimal harus :min karakter.',
    ],
    'max'      => [
        'string' => ':attribute maksimal :max karakter.',
    ],
    'attributes' => [
        'name'      => request()->is('proker*') ? 'Program Keahlian' : 'Nama Siswa',
        'nip'       => 'NIS',
        'email'     => 'Email',
        'password'  => 'Password',
        'kelas_id'  => 'Kelas',
        'konke_id'  => 'Konsentrasi Keahlian',
    ],


];