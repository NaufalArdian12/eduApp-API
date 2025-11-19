<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Demo Student',
                'password' => Hash::make('password'), // password: password
            ]
        );

        // User::updateOrCreate(
        //     ['email' => 'admin@example.com'],
        //     [
        //         'name' => 'Demo Admin',
        //         'password' => Hash::make('password'),
        //         'is_admin' => true,
        //     ]
        // );
    }
}
