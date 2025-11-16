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

        $this->call([
            SourceSeeder::class,
        ]);

        // create admin user directly here
        \App\Models\User::create([
            'name' => 'Mohammad Salahat',
            'email' => 'mohammadsalahat691@gmail.com',
            'password' => bcrypt('123456789'),
            'role' => 'admin',
        ]);
    }
}
