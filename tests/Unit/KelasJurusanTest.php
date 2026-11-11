<?php

namespace Tests\Unit;

use App\Support\KelasJurusan;
use PHPUnit\Framework\TestCase;

class KelasJurusanTest extends TestCase
{
    public function test_it_returns_the_mapped_classes_for_each_jurusan(): void
    {
        $this->assertSame(
            ['RPL 1', 'RPL 2', 'GIM'],
            KelasJurusan::namesFor('Pengembangan Perangkat Lunak dan Gim')
        );
        $this->assertSame(
            ['TKJ 1', 'TKJ 2', 'TKJ 3'],
            KelasJurusan::namesFor('Teknik Komputer dan Jaringan')
        );
        $this->assertSame(
            ['TKR 1', 'TKR 2', 'TKR 3'],
            KelasJurusan::namesFor('Teknik Kendaraan Ringan Otomotif')
        );
        $this->assertSame(
            ['MP 1', 'MP 2'],
            KelasJurusan::namesFor('Manajemen Perkantoran')
        );
        $this->assertSame(['AK 1', 'AK 2'], KelasJurusan::namesFor('Akuntansi'));
        $this->assertSame(['SK 1', 'SK 2'], KelasJurusan::namesFor('Seni Karawitan'));
        $this->assertSame(
            ['DPIB 1', 'DPIB 2'],
            KelasJurusan::namesFor('Desain Pemodelan dan Informasi Bangunan')
        );
    }

    public function test_it_returns_no_classes_for_an_unmapped_jurusan(): void
    {
        $this->assertSame([], KelasJurusan::namesFor('Desain Komunikasi Visual'));
    }
}
