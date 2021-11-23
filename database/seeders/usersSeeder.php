<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class usersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Karen',
            'email' => 'karen@gmail.com',
            'tipo' => 'admin',
            'password' => Hash::make('2001karen18'),
            'telefono' => '5613753427',
            'puntos' => 1000,
            'email_verified_at' => now(),
        ]);
    }
}
