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

    function simulateAllWeeks() {
        // Loop through all fixtures
        foreach (Fixture::all() as $fixture) {
            $homeTeamStats = $fixture->homeTeam->teamStats;
            $awayTeamStats = $fixture->awayTeam->teamStats;

            // Calculate score for home and away teams
            $homeTeamScore = $homeTeamStats->calculateTeamWeightScore() * (1 + rand(0, 2) / 10);
            $awayTeamScore = $awayTeamStats->calculateTeamWeightScore() * (1 + rand(0, 2) / 10);

            // Add a chance factor for less powerful team
            if ($homeTeamScore > $awayTeamScore) {
                $chanceFactor = $awayTeamStats->calculateWinningChanceFactor($homeTeamStats);
                $homeTeamScore *= (1 + $chanceFactor / 10);
            } elseif ($homeTeamScore < $awayTeamScore) {
                $chanceFactor = $homeTeamStats->calculateWinningChanceFactor($awayTeamStats);
                $awayTeamScore *= (1 + $chanceFactor / 10);
            }

            // Determine the winner or if it's a draw
            if ($homeTeamScore > $awayTeamScore) {
                $homeTeamGoals = rand(1, 5);
                $awayTeamGoals = rand(0, $homeTeamGoals - 1);
            } elseif ($homeTeamScore < $awayTeamScore) {
                $awayTeamGoals = rand(1, 5);
                $homeTeamGoals = rand(0, $awayTeamGoals - 1);
            } else {
                $homeTeamGoals = rand(0, 5);
                $awayTeamGoals = $homeTeamGoals;
            }

            // Update simulation stats for each team
            $homeTeamStats->played++;
            $awayTeamStats->played++;
            $homeTeamStats->goals_for += $homeTeamGoals;
            $awayTeamStats->goals_for += $awayTeamGoals;
            $homeTeamStats->goals_against += $awayTeamGoals;
            $awayTeamStats->goals_against += $homeTeamGoals;

            if ($homeTeamGoals > $awayTeamGoals) {
                $homeTeamStats->won++;
                $homeTeamStats->points += 3;
                $awayTeamStats->lost++;
            } elseif ($homeTeamGoals < $awayTeamGoals) {
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

            // Save the score for the fixture
            $fixture->home_team_score = $homeTeamGoals;
            $fixture->away_team_score = $awayTeamGoals;
            $fixture->save();
        }
    }

}
