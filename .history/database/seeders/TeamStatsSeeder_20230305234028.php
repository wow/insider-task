<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamStatsSeeder extends Seeder
{
    /**
     * Run the team stats seeds.
     */
    public function run(): void
    {
        DB::table('team_stats')->insert([
            'team_id' => 1,
            'matches_played' => 1178,
            'wins' => 639,
            'losses' => 252,
            'saves' => 1119,
            'penalties_saved' => 1,
            'punches' => 50,
            'high_claims' => 77,
            'catches' => 21,
            'throw_outs' => 603,
            'goal_kicks' => 845,
            'clean_sheets' => 447,
            'goals_conceded' => 1173,
            'tackles' => 11915,
            'tackle_success' => 71,
            'blocked_shots' => 2572,
            'interceptions' => 8984,
            'clearances' => 16001,
            'own_goals' => 47,
            'goals' => 2076,
            'penalties_scored' => 73,
            'shots' => 9722,
            'big_chances_created' => 874,
            'hit_woodwork' => 278,
            'passes' => 338431,
            'passes_accuracy' => 84,
            'crosses' => 13481,
            'cross_accuracy' => 21,
            'yellow_cards' => 1778,
            'red_cards' => 102,
            'fouls' => 1982,
            'offsides' => 1400,
        ], [
            'team_id' => 2,
            'matches_played' => 1178,
            'wins' => 621,
            'losses' => 268,
            'saves' => 847,
            'penalties_saved' => 2,
            'punches' => 70,
            'high_claims' => 73,
            'catches' => 25,
            'throw_outs' => 884,
            'goal_kicks' => 863,
            'clean_sheets' => 452,
            'goals_conceded' => 1177,
            'tackles' => 12857,
            'tackle_success' => 72,
            'blocked_shots' => 2806,
            'interceptions' => 7727,
            'clearances' => 15768,
            'own_goals' => 43,
            'goals' => 2068,
            'penalties_scored' => 82,
            'shots' => 10780,
            'big_chances_created' => 912,
            'hit_woodwork' => 300,
            'passes' => 340789,
            'passes_accuracy' => 82,
            'crosses' => 14354,
            'cross_accuracy' => 22,
            'yellow_cards' => 1462,
            'red_cards' => 61,
            'fouls' => 1952,
            'offsides' => 1400,
        ], [
            'team_id' => 3,
            'matches_played' => 988,
            'wins' => 491,
            'losses' => 283,
            'saves' => 715,
            'penalties_saved' => 3,
            'punches' => 52,
            'high_claims' => 92,
            'catches' => 24,
            'throw_outs' => 974,
            'goal_kicks' => 898,
            'clean_sheets' => 343,
            'goals_conceded' => 1093,
            'tackles' => 11657,
            'tackle_success' => 72,
            'blocked_shots' => 2778,
            'interceptions' => 8030,
            'clearances' => 15351,
            'own_goals' => 40,
            'goals' => 1724,
            'penalties_scored' => 91,
            'shots' => 10345,
            'big_chances_created' => 1050,
            'hit_woodwork' => 282,
            'passes' => 354787,
            'passes_accuracy' => 85,
            'crosses' => 13264,
            'cross_accuracy' => 22,
            'yellow_cards' => 1440,
            'red_cards' => 75,
            'fouls' => 1995,
            'offsides' => 1389,
        ], [
            'team_id' => 4,
            'matches_played' => 1177,
            'wins' => 627,
            'losses' => 259,
            'saves' => 873,
            'penalties_saved' => 2,
            'punches' => 46,
            'high_claims' => 92,
            'catches' => 19,
            'throw_outs' => 782,
            'goal_kicks' => 1048,
            'clean_sheets' => 472,
            'goals_conceded' => 1150,
            'tackles' => 12182,
            'tackle_success' => 71,
            'blocked_shots' => 2865,
            'interceptions' => 7750,
            'clearances' => 15054,
            'own_goals' => 41,
            'goals' => 1998,
            'penalties_scored' => 90,
            'shots' => 10558,
            'big_chances_created' => 791,
            'hit_woodwork' => 250,
            'passes' => 338365,
            'passes_accuracy' => 84,
            'crosses' => 13834,
            'cross_accuracy' => 23,
            'yellow_cards' => 1850,
            'red_cards' => 85,
            'fouls' => 2000,
            'offsides' => 1520,
        ]);
    }
}