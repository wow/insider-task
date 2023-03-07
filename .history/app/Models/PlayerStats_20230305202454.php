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
}
