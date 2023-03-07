<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'played',
        'won',
        'drawn',
        'lost',
        'goals_for',
        'goals_against',
        'goal_difference',
        'points',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    function simulateAllWeeks()
    {
        // Loop through all fixtures
        foreach (Fixture::all() as $fixture) {
            $homeTeamStats = $fixture->homeTeam->teamStats;
            $awayTeamStats = $fixture->awayTeam->teamStats;

            // Calculate score for home and away teams
            $homeTeamWeight = $homeTeamStats->calculateTeamWeightScore();
            $awayTeamWeight = $awayTeamStats->calculateTeamWeightScore();

            // Calculate probability of a home win, away win and draw
            $homeWinProb = $homeTeamWeight / ($homeTeamWeight + $awayTeamWeight);
            $awayWinProb = $awayTeamWeight / ($homeTeamWeight + $awayTeamWeight);
            $drawProb = 1 - $homeWinProb - $awayWinProb;

            // Determine the winner or if it's a draw based on the probabilities
            $result = rand(0, 100) / 100;
            if ($result < $homeWinProb) {
                $winner = 'home';
                $loser = 'away';
            } elseif ($result < $homeWinProb + $awayWinProb) {
                $winner = 'away';
                $loser = 'home';
            } else {
                $winner = 'draw';
                $loser = 'draw';
            }

            // Update simulation stats for each team
            $homeTeamStats->played++;
            $awayTeamStats->played++;
            $homeTeamStats->goals_for += $fixture->home_team_score;
            $awayTeamStats->goals_for += $fixture->away_team_score;
            $homeTeamStats->goals_against += $fixture->away_team_score;
            $awayTeamStats->goals_against += $fixture->home_team_score;

            if ($winner == 'home') {
                $homeTeamStats->won++;
                $homeTeamStats->points += 3;
                $awayTeamStats->lost++;
            } elseif ($winner == 'away') {
                $awayTeamStats->won++;
                $awayTeamStats->points += 3;
                $homeTeamStats->lost++;
            } else {
                $homeTeamStats->drawn++;
                $awayTeamStats->drawn++;
                $homeTeamStats->points++;
                $awayTeamStats->points++;
            }

            // Save the changes to the team stats
            $homeTeamStats->save();
            $awayTeamStats->save();
        }
    }

}
