<?php

namespace Database\Seeders;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'name'=>'hubin',
                'nip'=>'1234567890',
                'role'=>'hubin',
                'password'=>bcrypt('12345678')
            ],
            [
                'name'=>'siswa',
                'nip'=>'1234567891',
                'role'=>'siswa',
                'password'=>bcrypt('12345678')
            ],
            [
                'name'=>'kaprog',
                'nip'=>'1234567892',
                'role'=>'kaprog ',
                'password'=>bcrypt('12345678')
            ],
            [
                'name'=>'guru',
                'nip'=>'1234567893',
                'role'=>'guru ',
                'password'=>bcrypt('12345678')
            ],
            [
                'name'=>'Pembimbing pkl',
                'nip'=>'1234567894',
                'role'=>'ppkl ',
                'password'=>bcrypt('12345678')
            ],
            [
                 'name'=>'Pembimbing sekolah',
                'nip'=>'1234567895',
                'role'=>'psekolah ',
                'password'=>bcrypt('12345678')
            ],
            [
                'name'=>'iduka',
                'nip'=>'iduka@gmail.com',
                'role'=>'iduka ',
                'password'=>bcrypt('12345678')
            ],
            [
                'name'=>'orangtua',
                'nip'=>'1234567896',
                'role'=>'orangtua ',
                'password'=>bcrypt('12345678')
            ],
            [
                'name'=>'persuratan',
                'nip'=>'1234567897',
                'role'=>'persuratan ',
                'password'=>bcrypt('12345678')
            ],
        ];

        foreach($userData as $key => $val){
            User::create($val);
        }
    }
}
