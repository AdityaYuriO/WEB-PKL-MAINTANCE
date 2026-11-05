<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SiswaKelasImport implements ToModel, WithStartRow
{
    protected $kelas_id;
    protected $konke_id;

    // Terima parameter kelas_id dan konke_id dari controller
    public function __construct($kelas_id, $konke_id)
    {
        $this->kelas_id = $kelas_id;
        $this->konke_id = $konke_id;
    }

    public function startRow(): int
    {
        return 2; // Skip header
    }

    public function model(array $row)
    {
        return new User([
            'name'     => $row[0], // Kolom A: Nama
            'nip'      => $row[1], // Kolom B: NIS
            'kelas_id' => $this->kelas_id, // Diambil dari parameter
            'konke_id' => $this->konke_id, // Diambil dari parameter
            'email'    => $row[2], // Kolom C: Email
            'password' => Hash::make($row[3]), // Kolom D: Password
            'role'     => 'siswa',
        ]);
    }
}