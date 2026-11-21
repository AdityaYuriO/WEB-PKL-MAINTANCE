<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow; // <-- Tambahkan ini

class SiswaImport implements ToModel, WithStartRow // <-- Implementasikan
{
    public function startRow(): int
    {
        return 2; // Mulai baca data dari baris ke-2 (abaikan baris 1 = header)
    }

    public function model(array $row)
    {
        return new User([
            'name'     => $row[0], // Kolom A (Nama)
            'nip'      => $row[1], // Kolom B (NIS)
            'kelas_id'    => $row[2], // Kolom C (Kelas)
            'konke_id'   => $row[3], // Kolom D (Konke)
            'email'    => $row[4], // Kolom E (Email)
            'password' => Hash::make($row[5]), // Kolom F (Password)
            'role'     => 'siswa',
        ]);
    }
}