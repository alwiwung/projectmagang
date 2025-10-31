<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Cek apakah admin sudah ada
        $adminEmail = 'admin@warkah.id';
        
        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => $adminEmail,
                'password' => Hash::make('admin123'),
            ]);
            
            $this->command->info('✓ Admin user created successfully!');
        } else {
            $this->command->info('✓ Admin user already exists.');
        }

        // User Demo tambahan (optional)
        $demoUsers = [
            [
                'name' => 'User Demo',
                'email' => 'user@warkah.id',
                'password' => Hash::make('user123'),
            ],
            [
                'name' => 'Demo Account',
                'email' => 'demo@warkah.id',
                'password' => Hash::make('demo123'),
            ],
        ];

        foreach ($demoUsers as $userData) {
            if (!User::where('email', $userData['email'])->exists()) {
                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => $userData['password'],
                ]);
                
                $this->command->info("✓ User {$userData['email']} created successfully!");
            }
        }
    }
}