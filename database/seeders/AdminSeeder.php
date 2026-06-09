<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@panenku.com'],
            [
                'name'     => 'Admin PanenKu',
                'email'    => 'admin@panenku.com',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );
    }
}