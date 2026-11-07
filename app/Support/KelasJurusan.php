<?php

namespace App\Support;

class KelasJurusan
{
    private const NAMES_BY_JURUSAN = [
        'Pengembangan Perangkat Lunak & Gim' => ['RPL 1', 'RPL 2', 'GIM'],
        'Rekayasa Perangkat Lunak' => ['RPL 1', 'RPL 2', 'GIM'],
        'Teknik Komputer dan Jaringan' => ['TKJ 1', 'TKJ 2', 'TKJ 3'],
        'Teknik Kendaraan Ringan' => ['TKR 1', 'TKR 2', 'TKR 3'],
        'Teknik Kendaraan Ringan Otomotif' => ['TKR 1', 'TKR 2', 'TKR 3'],
        'Manajemen Perkantoran' => ['MP 1', 'MP 2'],
        'Akuntansi' => ['AK 1', 'AK 2'],
        'Seni Karawitan' => ['SK 1', 'SK 2'],
        'Desain Pemodelan dan Informasi Bangunan' => ['DPIB 1', 'DPIB 2'],
    ];

    public static function namesFor(?string $jurusan): array
    {
        if ($jurusan === null) {
            return [];
        }

        $normalizedJurusan = self::normalize($jurusan);
        foreach (self::NAMES_BY_JURUSAN as $name => $classes) {
            if ($normalizedJurusan === self::normalize($name)) {
                return $classes;
            }
        }

        return [];
    }

    public static function optionsByKonkeId(iterable $konkes): array
    {
        $options = [];

        foreach ($konkes as $konke) {
            $options[$konke->id] = self::namesFor($konke->name_konke);
        }

        return $options;
    }

    private static function normalize(string $value): string
    {
        $normalized = strtolower(str_replace('&', ' dan ', trim($value)));

        return preg_replace('/\s+/', ' ', $normalized) ?? $normalized;
    }
}
