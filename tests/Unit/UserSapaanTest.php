<?php

namespace Tests\Unit;

use App\Models\DataPribadi;
use App\Models\DataPribadiPersuratan;
use App\Models\Guru;
use App\Models\Kependik;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserSapaanTest extends TestCase
{
    public function test_it_uses_gender_from_each_role_profile(): void
    {
        $student = new User(['role' => 'siswa']);
        $student->setRelation('dataPribadi', new DataPribadi(['jk' => 'laki_laki']));

        $teacher = new User(['role' => 'guru']);
        $teacher->setRelation('guru', new Guru(['jenis_kelamin' => 'Perempuan']));

        $staff = new User(['role' => 'pendamping']);
        $staff->setRelation('kependik', new Kependik(['jenis_kelamin' => 'Laki-laki']));

        $secretariat = new User(['role' => 'persuratan']);
        $secretariat->setRelation('dataPersuratan', new DataPribadiPersuratan(['jk' => 'perempuan']));

        $this->assertSame('Bapak', $student->sapaan());
        $this->assertSame('Ibu', $teacher->sapaan());
        $this->assertSame('Bapak', $staff->sapaan());
        $this->assertSame('Ibu', $secretariat->sapaan());
    }

    public function test_it_omits_the_salutation_when_gender_is_unavailable(): void
    {
        $this->assertNull((new User(['role' => 'iduka']))->sapaan());
        $this->assertNull((new User(['role' => 'orangtua']))->sapaan());
    }
}
