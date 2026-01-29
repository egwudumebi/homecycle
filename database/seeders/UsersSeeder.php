<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@homecycle.ng'],
            [
                'name' => 'Chinedu Okafor',
                'phone' => '08031234567',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $names = [
            'Aisha Bello',
            'Tunde Adeyemi',
            'Chioma Eze',
            'Ibrahim Musa',
            'Kemi Ogunleye',
            'Emeka Nwosu',
            'Seyi Adebayo',
            'Zainab Abdullahi',
        ];

        foreach ($names as $name) {
            User::factory()->create([
                'name' => $name,
                'role' => 'user',
                'email_verified_at' => now(),
            ]);
        }
    }
}
