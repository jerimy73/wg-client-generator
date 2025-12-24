<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'jeri.maulanayusuf1@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('SuperM@n2299!'),
                'is_admin' => true,
            ]
        );
    }
}
