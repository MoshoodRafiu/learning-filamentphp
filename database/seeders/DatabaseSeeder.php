<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Student;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Moshood',
            'email' => 'moshood@test.com',
        ]);

        $this->call(StudentSeeder::class);
        $this->call(StandardSeeder::class);
    }
}
