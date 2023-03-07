<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            FixtureSeeder::class,
            GameSeeder::class,
            TeamSeeder::class,
            TeamStatsSeeder::class,
        ]);
    }
}
