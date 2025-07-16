<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@laravelshare.local',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
