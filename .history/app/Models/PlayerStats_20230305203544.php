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

    /**
     * Calculate the player's score.
     *
     * @return float
     */
    function calculatePlayerWeightScore($playerStats): float
    {
        // Define weights for each category
        $weights = [
            'goalkeeping' => 0.2,
            'defence' => 0.3,
            'attack' => 0.3,
            'teamPlay' => 0.1,
            'discipline' => 0.1,
        ];

        // Calculate score for each category
        $scores = [
            'goalkeeping' => ($playerStats['saves'] * 0.5) +
                            ($playerStats['penalties_saved'] * 2) +
                            ($playerStats['punches'] * 0.5) +
                            ($playerStats['high_claims'] * 1.5) +
                            ($playerStats['catches'] * 1) +
                            ($playerStats['throw_outs'] * 0.5) +
                            ($playerStats['goal_kicks'] * 1),

            'defence' => ($playerStats['clean_sheets'] * 3) +
                        ($playerStats['tackles'] * 1.5) +
                        ($playerStats['tackle_success'] * 1) +
                        ($playerStats['blocked_shots'] * 1.5) +
                        ($playerStats['interceptions'] * 2) +
                        ($playerStats['clearances'] * 1.5) +
                        ($playerStats['recoveries'] * 1) +
                        ($playerStats['duels_won'] * 1.5) -
                        ($playerStats['duels_lost'] * 1) -
                        ($playerStats['own_goals'] * 3),

            'attack' => ($playerStats['goals'] * 4) +
                        ($playerStats['headed_goals'] * 2) +
                        ($playerStats['penalties_scored'] * 3) +
                        ($playerStats['free_kicks_scored'] * 3) +
                        ($playerStats['shots'] * 0.5),

            'teamPlay' => ($playerStats['assists'] * 3) +
                        ($playerStats['passes'] * 0.01) +
                        ($playerStats['big_chances_created'] * 2) +
                        ($playerStats['crosses'] * 1.5) +
                        ($playerStats['cross_accuracy'] * 0.5) +
                        ($playerStats['through_balls'] * 2) +
                        ($playerStats['accurate_long_balls'] * 1),

            'discipline' => ($playerStats['yellow_cards'] * -1) +
                            ($playerStats['red_cards'] * -3) +
                            ($playerStats['fouls'] * -0.5) +
                            ($playerStats['offsides'] * -0.5),
        ];

        // Calculate weighted average score
        $weightedScore = 0;
        foreach ($weights as $category => $weight) {
            $weightedScore += $scores[$category] * $weight;
        }

        return $weightedScore;
    }


}
