<?php

namespace Database\Seeders;

use App\Models\Guardian;
use App\Models\Student;
use App\Models\Standard;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::factory(10)
            ->has(Guardian::factory()->count(3))
            ->create();
    }
}
