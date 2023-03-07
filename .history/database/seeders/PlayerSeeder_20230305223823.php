<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Team 1 - Arsenal
        DB::table('players')->insert([
            'name' => 'Aaron Ramsdale',
            'team_id' => 1,
            'number' => 1,
            'position' => 'Goalkeeper',
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'name' => 'Gabriel Magalhães',
            'team_id' => 1,
            'number' => 6,
            'position' => 'Defender',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'William Saliba',
            'team_id' => 1,
            'number' => 12,
            'position' => 'Defender',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Takehiro Tomiyasu',
            'team_id' => 1,
            'number' => 18,
            'position' => 'Defender',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Oleksandr Zinchenko',
            'team_id' => 1,
            'number' => 35,
            'position' => 'Defender',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Thomas Partey',
            'team_id' => 1,
            'number' => 5,
            'position' => 'Midfielder',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Martin Ødegaard',
            'team_id' => 1,
            'number' => 8,
            'position' => 'Midfielder',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Fábio Vieira',
            'team_id' => 1,
            'number' => 21,
            'position' => 'Midfielder',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Bukayo Saka',
            'team_id' => 1,
            'number' => 7,
            'position' => 'Midfielder',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Gabriel Martinelli',
            'team_id' => 1,
            'number' => 11,
            'position' => 'Forward',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Leandro Trossard',
            'team_id' => 1,
            'number' => 19,
            'position' => 'Forward',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Team 2 - Liverpool
        DB::table('players')->insert([
            'name' => 'Alisson Becker',
            'team_id' => 2,
            'number' => 1,
            'position' => 'Goalkeeper',
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'name' => 'Virgil van Dijk',
            'team_id' => 2,
            'number' => 4,
            'position' => 'Defender',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Ibrahima Konaté',
            'team_id' => 2,
            'number' => 5,
            'position' => 'Defender',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Andrew Robertson',
            'team_id' => 2,
            'number' => 26,
            'position' => 'Defender',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Trent Alexander-Arnold',
            'team_id' => 2,
            'number' => 66,
            'position' => 'Defender',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Georginio Wijnaldum',
            'team_id' => 2,
            'number' => 5,
            'position' => 'Midfielder',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'James Milner',
            'team_id' => 2,
            'number' => 7,
            'position' => 'Midfielder',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Alex Oxlade-Chamberlain',
            'team_id' => 2,
            'number' => 21,
            'position' => 'Midfielder',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
