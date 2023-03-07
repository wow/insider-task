<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamStats extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'matches_played',
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
        'own_goals',
        'goals',
        'penalties_scored',
        'shots',
        'big_chances_created',
        'hit_woodwork',
        'passes',
        'passes_accuracy',
        'crosses',
        'cross_accuracy',
        'yellow_cards',
        'red_cards',
        'fouls',
        'offsides',
    ];

    // get the team
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // get the team's score
    public function getTeamScore($teamId)
    {
        $teamStats = TeamStats::where('team_id', $teamId)->first();

        return $this->calculateTeamWeightScore($teamStats);
    }

    /**
     * Calculate the team's score.
     *
     * @return float
     */
    private function calculateTeamWeightScore(): float
    {
        $teamStats = $this->toArray();

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
            'goalkeeping' => ($teamStats['saves'] * 0.5) +
                            ($teamStats['penalties_saved'] * 2) +
                            ($teamStats['punches'] * 0.5) +
                            ($teamStats['high_claims'] * 1.5) +
                            ($teamStats['catches'] * 1) +
                            ($teamStats['throw_outs'] * 0.5) +
                            ($teamStats['goal_kicks'] * 1),

            'defence' => ($teamStats['clean_sheets'] * 3) +
                        ($teamStats['goals_conceded'] * -2) +
                        ($teamStats['tackles'] * 1.5) +
                        ($teamStats['tackle_success'] * 1) +
                        ($teamStats['blocked_shots'] * 1.5) +
                        ($teamStats['interceptions'] * 2) +
                        ($teamStats['clearances'] * 1.5) +
                        ($teamStats['own_goals'] * 3),

            'attack' => ($teamStats['goals'] * 4) +
                        ($teamStats['penalties_scored'] * 3) +
                        ($teamStats['shots'] * 0.5),
                        ($teamStats['big_chances_created'] * 2) +
                        ($teamStats['hit_woodwork'] * 1),

            'teamPlay' => ($teamStats['passes'] * 0.01) +
                        ($teamStats['passes_accuracy'] * 0.5) +
                        ($teamStats['crosses'] * 1.5) +
                        ($teamStats['cross_accuracy'] * 0.5),

            'discipline' => ($teamStats['yellow_cards'] * -1) +
                            ($teamStats['red_cards'] * -3) +
                            ($teamStats['fouls'] * -0.5) +
                            ($teamStats['offsides'] * -0.5),
        ];

        // Calculate weighted average score
        $weightedScore = 0;
        foreach ($weights as $category => $weight) {
            $weightedScore += $scores[$category] * $weight;
        }

        return $weightedScore;
    }
}
