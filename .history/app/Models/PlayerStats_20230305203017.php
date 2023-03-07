<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerStats extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'appearances',
        'wins',
        'losses',
        'saves',
        'penalties_saved',
        'punches',
        'high_claims',
        'catches',
        'throw_outs',
        'goal_kicks',
        'clean_sheets',
        'goals_conceded',
        'tackles',
        'tackle_success',
        'blocked_shots',
        'interceptions',
        'clearances',
        'recoveries',
        'duels_won',
        'duels_lost',
        'own_goals',
        'goals',
        'headed_goals',
        'penalties_scored',
        'free_kicks_scored',
        'shots',
        'assists',
        'passes',
        'big_chances_created',
        'crosses',
        'cross_accuracy',
        'through_balls',
        'accurate_long_balls',
        'yellow_cards',
        'red_cards',
        'fouls',
        'offsides',
    ];

    // get the player that owns the stats
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    // calculate player weight score
    function calculatePlayerWeightScore($playerStats) {
        // Calculate weighted values for each category
        $goalkeepingWeight = 0.2;
        $defenceWeight = 0.3;
        $attackWeight = 0.3;
        $teamPlayWeight = 0.1;
        $disciplineWeight = 0.1;

        $goalkeepingScore = ($playerStats['saves'] * 0.5) + ($playerStats['penalties_saved'] * 2) +
                            ($playerStats['punches'] * 0.5) + ($playerStats['high_claims'] * 1.5) +
                            ($playerStats['catches'] * 1) + ($playerStats['throw_outs'] * 0.5) +
                            ($playerStats['goal_kicks'] * 1);

        $defenceScore = ($playerStats['clean_sheets'] * 3) + ($playerStats['tackles'] * 1.5) +
                        ($playerStats['tackle_success'] * 1) + ($playerStats['blocked_shots'] * 1.5) +
                        ($playerStats['interceptions'] * 2) + ($playerStats['clearances'] * 1.5) +
                        ($playerStats['recoveries'] * 1) + ($playerStats['duels_won'] * 1.5) -
                        ($playerStats['duels_lost'] * 1) - ($playerStats['own_goals'] * 3);

        $attackScore = ($playerStats['goals'] * 4) + ($playerStats['headed_goals'] * 2) +
                        ($playerStats['penalties_scored'] * 3) + ($playerStats['free_kicks_scored'] * 3) +
                        ($playerStats['shots'] * 0.5);

        $teamPlayScore = ($playerStats['assists'] * 3) + ($playerStats['passes'] * 0.01) +
                         ($playerStats['big_chances_created'] * 2) + ($playerStats['crosses'] * 1.5) +
                         ($playerStats['cross_accuracy'] * 0.5) + ($playerStats['through_balls'] * 2) +
                         ($playerStats['accurate_long_balls'] * 1);

        $disciplineScore = ($playerStats['yellow_cards'] * -1) + ($playerStats['red_cards'] * -3) +
                           ($playerStats['fouls'] * -0.5) + ($playerStats['offsides'] * -0.5);

        // Calculate weighted average score
        $weightedScore = ($goalkeepingScore * $goalkeepingWeight) + ($defenceScore * $defenceWeight) +
                         ($attackScore * $attackWeight) + ($teamPlayScore * $teamPlayWeight) +
                         ($disciplineScore * $disciplineWeight);

        return $weightedScore;
    }

}