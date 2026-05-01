<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = env('ADMIN_PASSWORD');
        
        User::create([
            'name'              => 'Wareed Admin',
            'email'             => 'admin@wareed.com',
            'password'          => Hash::make($password),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}
