<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            ['name' => 'Student', 'description' => 'Can access courses', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Teacher', 'description' => 'Manages students and grading', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Instructor', 'description' => 'Creates and manages courses', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Admin', 'description' => 'Full system access', 'created_at' => now(), 'updated_at' => now()],

        ]);
    }
}
