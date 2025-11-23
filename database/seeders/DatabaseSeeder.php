<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First, run the SQL file seeder to import all data from mysql.sql
        $this->call([
            SqlFileSeeder::class,
        ]);

        // Then run other seeders
        $this->call([
            SourceSeeder::class,
        ]);

        // Create admin user if it doesn't exist
        if (!\App\Models\User::where('email', 'walajmi50@gmail.com')->exists()) {
            \App\Models\User::create([
                'name' => 'Wadha Al-Ajmi',
                'email' => 'walajmi50@gmail.com',
                'password' => bcrypt('123456789'),
                'role' => 'admin',
            ]);
            $this->command->info('✅ Admin user created');
        } else {
            $this->command->info('ℹ️  Admin user already exists');
        }
    }
}
