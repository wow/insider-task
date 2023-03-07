<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Game;
use App\Models\Simulation;
use App\Models\Team;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/**
 * Class PredictionService
 */
class SimulationService
{
    public function __construct(Private Simulation $simulation)
    {
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
        $this->simulation->resetSimulation();

        $fixturesByWeek = Fixture::with(['homeTeam', 'awayTeam'])->orderBy('week')->get()->groupBy('week');
        foreach ($fixturesByWeek as $week => $fixtures) {
            $this->simulateFixtures($fixtures);
        }
    }

    public function reset(Fixture $fixture)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Game::truncate();
        Simulation::truncate();
        Fixture::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Artisan::call('db:seed', ['--class' => 'SimulationSeeder']);

        $fixture->generateFixturesForTeams();
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
                $homeTeamWinChance += 0.03;
            } elseif ($homeTeamWeightScore > $awayTeamWeightScore) {
                $awayTeamWinChance += 0.03;
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
}
