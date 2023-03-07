<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Standings table
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

    // Calculate simulation weight score
    public function calculateSimulationWeightScore()
    {
        $weightScore = 0;

        $weightScore += $this->won * 3;
        $weightScore += $this->drawn;
        $weightScore += $this->lost * -2;
        $weightScore += $this->goals_for * 1.5;
        $weightScore += $this->goals_against * -1.8;
        $weightScore += $this->goal_difference * 1.7;
        $weightScore += $this->points * 1.2;

        return $weightScore;
    }

    // Get current standings from the simulated data
    public function getCurrentStandings()
    {
        $standings = Simulation::with('team')->orderBy('points', 'desc')->orderBy('goal_difference', 'desc')->get();

        return $standings;
    }

    // Get current week
    public function getCurrentWeek()
    {
        $currentWeek = Simulation::max('played');

        return $currentWeek;
    }

    // Get current Unplayed week
    public function getCurrentUnplayedWeek()
    {
        $currentWeek = Simulation::min('played') + 1;

        return $currentWeek;
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function simulateByWeek($week)
    {
        // get fixtures for the week
        $fixtures = Fixture::with(['homeTeam', 'awayTeam'])->where('week', $week)->get();
        $this->simulateFixtures($fixtures);
    }

    /**
     * Simulate all games
     */
    public function simulateAllGames()
    {
        $this->resetSimulation();

        $fixturesByWeek = Fixture::with(['homeTeam', 'awayTeam'])->orderBy('week')->get()->groupBy('week');
        foreach ($fixturesByWeek as $week => $fixtures) {
            $this->simulateFixtures($fixtures);
        }
    }

    /**
     * Simulate the fixtures
     */
    private function simulateFixtures($fixtures)
    {
        foreach ($fixtures as $fixture) {
            $homeTeam = $fixture->homeTeam()->first();
            $awayTeam = $fixture->awayTeam()->first();

            $homeTeamWeightScore = $homeTeam->teamStats->calculateTeamWeightScore();
            $awayTeamWeightScore = $awayTeam->teamStats->calculateTeamWeightScore();

            $homeTeamPlayedGames = $homeTeam->teamStats->matches_played;
            $awayTeamPlayedGames = $awayTeam->teamStats->matches_played;

            // Calculate the winning chance for each team based on their weight score and previous games played
            $homeTeamWinChance = ($homeTeamWeightScore + $homeTeamPlayedGames) / ($homeTeamWeightScore + $awayTeamWeightScore + $homeTeamPlayedGames + $awayTeamPlayedGames);
            $awayTeamWinChance = ($awayTeamWeightScore + $awayTeamPlayedGames) / ($homeTeamWeightScore + $awayTeamWeightScore + $homeTeamPlayedGames + $awayTeamPlayedGames);

            // Add a little bit of chance factor for less powerful teams
            if ($homeTeamWeightScore < $awayTeamWeightScore) {
                $homeTeamWinChance += 0.01;
            } elseif ($homeTeamWeightScore > $awayTeamWeightScore) {
                $awayTeamWinChance += 0.01;
            }

            // Simulate the game and update the scores and stats for each team
            $homeTeamScore = 0;
            $awayTeamScore = 0;

            for ($i = 1; $i <= rand(1, 5); $i++) {
                $randomNumber = mt_rand(1, 100);

                if ($randomNumber <= ($homeTeamWinChance * 100)) {
                    $homeTeamScore++;
                } else {
                    $awayTeamScore++;
                }
            }

            // Update game table
            $game = $fixture->games()->first();
            $game->update([
                'home_team_score' => $homeTeamScore,
                'away_team_score' => $awayTeamScore,
                'home_team_weight_score' => $homeTeamWeightScore,
                'away_team_weight_score' => $awayTeamWeightScore,
                'played' => true,
            ]);

            // Update simulation table for each team
            $this->updateSimulation($homeTeam, $awayTeam, $homeTeamScore, $awayTeamScore);

            // Update team stats table for each team
            $homeTeamStats = $homeTeam->teamStats;
            $awayTeamStats = $awayTeam->teamStats;

            $homeTeamStats->matches_played++;
            $awayTeamStats->matches_played++;

            if ($homeTeamScore > $awayTeamScore) {
                $homeTeamStats->wins++;
                $awayTeamStats->losses++;
            } elseif ($homeTeamScore < $awayTeamScore) {
                $awayTeamStats->wins++;
                $homeTeamStats->losses++;
            }

            $homeTeamStats->goals += $homeTeamScore;
            $awayTeamStats->goals += $awayTeamScore;

            $homeTeamStats->save();
            $awayTeamStats->save();
        }
    }

    /**
     * Update Simulation table for each team
     */
    private function updateSimulation($homeTeam, $awayTeam, $homeTeamScore, $awayTeamScore)
    {
        $homeTeamSimulation = $homeTeam->simulation;
        $awayTeamSimulation = $awayTeam->simulation;

        // Update simulation table for each team
        $homeTeamSimulation->played++;
        $awayTeamSimulation->played++;

        if ($homeTeamScore > $awayTeamScore) {
            $homeTeamSimulation->won++;
            $homeTeamSimulation->points += 3;
            $awayTeamSimulation->lost++;
        } elseif ($homeTeamScore < $awayTeamScore) {
            $awayTeamSimulation->won++;
            $awayTeamSimulation->points += 3;
            $homeTeamSimulation->lost++;
        } else {
            $homeTeamSimulation->drawn++;
            $homeTeamSimulation->points++;
            $awayTeamSimulation->drawn++;
            $awayTeamSimulation->points++;
        }

        $homeTeamSimulation->goals_for += $homeTeamScore;
        $homeTeamSimulation->goals_against += $awayTeamScore;
        $homeTeamSimulation->goal_difference = $homeTeamSimulation->goals_for - $homeTeamSimulation->goals_against;

        $awayTeamSimulation->goals_for += $awayTeamScore;
        $awayTeamSimulation->goals_against += $homeTeamScore;
        $awayTeamSimulation->goal_difference = $awayTeamSimulation->goals_for - $awayTeamSimulation->goals_against;

        $homeTeamSimulation->save();
        $awayTeamSimulation->save();
    }

    /**
     * Reset the simulation table
     */
    private function resetSimulation()
    {
        $teams = Team::all();

        foreach ($teams as $team) {
            $team->simulation()->update([
                'played' => 0,
                'won' => 0,
                'drawn' => 0,
                'lost' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'points' => 0,
            ]);
        }
    }
}
