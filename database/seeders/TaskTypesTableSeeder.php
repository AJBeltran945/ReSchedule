<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('task_types')->insert([
            [
                'name' => 'Small Task',
                'description' => 'A task that requires minimal effort or time.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Important Task',
                'description' => 'A task with high priority or significance.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Connected Task',
                'description' => 'A task linked to or dependent on another.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
