<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrioritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('priorities')->insert([
            [
                'name' => 'Normal',
                'importance' => 'Default priority level.',
                'color' => '#10B981', // Green
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Important',
                'importance' => 'High importance and urgency.',
                'color' => '#DC2626', // Red
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intermediate',
                'importance' => 'Moderate priority between normal and important.',
                'color' => '#F59E0B', // Amber
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
