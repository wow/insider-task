<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PlayerRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('player_records')->insert([
            'player_id' => 1,
            'fixture_id' => 1,
            'goals' => 1,
            'assists' => 1,
            'clean_sheets' => 1,
            'yellow_cards' => 1,
            'red_cards' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'player_id' => 2,
            'fixture_id' => 1,
            'goals' => 1,
            'assists' => 1,
            'clean_sheets' => 1,
            'yellow_cards' => 1,
            'red_cards' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'player_id' => 3,
            'fixture_id' => 1,
            'goals' => 1,
            'assists' => 1,
            'clean_sheets' => 1,
            'yellow_cards' => 1,
            'red_cards' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'player_id' => 4,
            'fixture_id' => 1,
            'goals' => 1,
            'assists' => 1,
            'clean_sheets' => 1,
            'yellow_cards' => 1,
            'red_cards' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'player_id' => 5,
            'fixture_id' => 1,
            'goals' => 1,
            'assists' => 1,
            'clean_sheets' => 1,
            'yellow_cards' => 1,
            'red_cards' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'player_id' => 6,
            'fixture_id' => 1,
            'goals' => 1,
            'assists' => 1,
            'clean_sheets' => 1,
            'yellow_cards' => 1,
            'red_cards' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'player_id' => 7,
            'fixture_id' => 1,
            'goals' => 1,
            'assists' => 1,
            'clean_sheets' => 1,
            'yellow_cards' => 1,
            'red_cards' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'player_id' => 8,
            'fixture_id' => 1,
            'goals' => 1,
            'assists' => 1,
            'clean_sheets' => 1,
            'yellow_cards' => 1,
            'red_cards' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'player_id' => 9,
            'fixture_id' => 1,
            'goals' => 1,
            'assists' => 1,
            'clean_sheets' => 1,
            'yellow_cards' => 1,
            'red_cards' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'player_id' => 10,
            'fixture_id' => 1,
            'goals' => 1,
            'assists' => 1,
            'clean_sheets' => 1,
            'yellow_cards' => 1,
            'red_cards' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'player_id' => 11,
            'fixture_id' => 1,
            'goals' => 1,
            'assists' => 1,
            'clean_sheets' => 1,
            'yellow_cards' => 1,
            'red_cards' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'player_id' => 12,
            'fixture_id' => 1,
            'goals' => 1,
            'assists' => 1,
            'clean_sheets' => 1,
            'yellow_cards' => 1,
            'red_cards' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
