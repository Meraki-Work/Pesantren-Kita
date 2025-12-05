<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@pesantren.id',
                'password' => Hash::make('password123'),
                'role' => 'Admin',
                'ponpes_id' => 'a1b2c3d4e5f6g7h8i9j0',
                'email_verified_at' => now(),
            ],
            [
                'username' => 'test',
                'email' => 'test@pesantren.id',
                'password' => Hash::make('test123'),
                'role' => 'Pengajar',
                'ponpes_id' => 'z9y8x7w6v5u4t3s2r1q0',
                'email_verified_at' => now(),
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('User berhasil dibuat!');
        $this->command->info('Email: admin@pesantren.id / Password: password123');
        $this->command->info('Email: test@pesantren.id / Password: test123');
    }
}