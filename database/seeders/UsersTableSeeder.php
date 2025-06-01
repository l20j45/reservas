<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'nombres' => 'admin',
            'apellidos' => 'Principal',
            'telefono' => '123456789',
            'email' => 'admin@test.com',
            'foto' => 'https://www.gravatar.com/avatar/',
            'password' => Hash::make('password'),
            'rol_id' => 1,
        ]);

        User::factory()->count(3)->create([
            'rol_id' => 2,
        ]);

        User::factory()->count(10)->create([
            'rol_id' => 3,
        ]);
    }
}
