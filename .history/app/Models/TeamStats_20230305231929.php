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
    public function getPlayerScore($teamId)
    {
        $playerStats = TeamStats::where('team_id', $teamId)->first();

        return $this->calculateTeamWeightScore($playerStats);
    }

    /**
     * Calculate the team's score.
     *
     * @return float
     */
    private function calculateTeamWeightScore($playerStats): float
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
                        ($playerStats['goals_conceded'] * -2) +
                        ($playerStats['tackles'] * 1.5) +
                        ($playerStats['tackle_success'] * 1) +
                        ($playerStats['blocked_shots'] * 1.5) +
                        ($playerStats['interceptions'] * 2) +
                        ($playerStats['clearances'] * 1.5) +
                        ($playerStats['own_goals'] * 3),

            'attack' => ($playerStats['goals'] * 4) +
                        ($playerStats['penalties_scored'] * 3) +
                        ($playerStats['shots'] * 0.5),
                        ($playerStats['big_chances_created'] * 2) +
                        ($playerStats['hit_woodwork'] * 1),

            'teamPlay' => ($playerStats['passes'] * 0.01) +
                        ($playerStats['passes_accuracy'] * 0.5) +
                        ($playerStats['crosses'] * 1.5) +
                        ($playerStats['cross_accuracy'] * 0.5),

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
