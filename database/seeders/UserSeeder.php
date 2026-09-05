<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Administrator PT BA',
            'email' => 'admin@reveg.test',
            'password' => Hash::make('password123'), // Bcrypt
            'role' => 'admin',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Admin',
        ]);

        // 2. Manager User
        User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Manager Reklamasi PAMA',
            'email' => 'manager@reveg.test',
            'password' => Hash::make('password123'), // Bcrypt
            'role' => 'manager',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Manager',
        ]);

        // 3. Surveyor User (Surveyor Tambang Lapangan)
        User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Surveyor Tambang Tanjung Enim',
            'email' => 'surveyor@reveg.test',
            'password' => Hash::make('password123'), // Bcrypt
            'role' => 'surveyor',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Surveyor',
        ]);

        // 4. Public User
        User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Warga Muara Enim',
            'email' => 'public@reveg.test',
            'password' => Hash::make('password123'), // Bcrypt
            'role' => 'public',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=PublicUser',
        ]);
    }
}
