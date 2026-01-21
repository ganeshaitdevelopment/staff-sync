<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Administrator (Login pake Email)
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@staffsync.com',
            'phone_number' => '081234567890',
            'role' => 'administrator',
            'password' => Hash::make('password'), // password default
        ]);

        // 2. Supervisor (Login pake Email)
        User::create([
            'name' => 'Manager HR',
            'email' => 'manager@staffsync.com',
            'phone_number' => '081234567891',
            'role' => 'supervisor',
            'password' => Hash::make('password'),
        ]);

        // 3. User Biasa / Staff (Login pake No HP)
        User::create([
            'name' => 'Budi Staff',
            'email' => null, // Staff lapangan gak wajib email
            'phone_number' => '081299998888', // Nanti login pake ini
            'role' => 'user',
            'password' => Hash::make('password'),
        ]);
    }
}
