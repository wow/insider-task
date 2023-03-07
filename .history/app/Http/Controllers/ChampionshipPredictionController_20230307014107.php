<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Simulation;

class ChampionshipPredictionController extends Controller
{
    public function predictChampionship()
    {
        $simulator = new Simulation();

        // Get current standings from the simulated data
        $teams = $simulator->getCurrentStandings();

        // Get remaining fixtures and calculate the simulation weight score for each team
        $remainingFixtures = Fixture::where('week', '>', $simulator->getCurrentWeek())->get();
        foreach ($teams as $team) {
            $team->simulationWeightScore = $this->calculateSimulationWeightScore($team, $remainingFixtures->count());
        }

        // Calculate championship prediction for each team
        $totalPoints = $teams->sum('points');
        $teams->each(function ($team) use ($totalPoints) {
            $team->championshipPrediction = $team->points / $totalPoints;
        });

        return $teams;
    }

    public function calculateSimulationWeightScore($team, $remainingFixtures)
    {
        $totalScore = 0;
        $totalWeight = 0;

        $simulator = new Simulation();

        foreach ($remainingFixtures as $fixture) {
            $homeTeam = $fixture->homeTeam()->first();
            $awayTeam = $fixture->awayTeam()->first();

            if ($homeTeam->id == $team->id || $awayTeam->id == $team->id) {
                $homeTeamStats = $homeTeam->simulation;
                $awayTeamStats = $awayTeam->simulation;

                $homeTeamWeight = $simulator->calculateSimulationWeightScore();
                $awayTeamWeight = $simulator->calculateSimulationWeightScore();

                $totalWeight += $homeTeamWeight + $awayTeamWeight;

                if ($homeTeam->id == $team->id) {
                    $totalScore += $homeTeamStats->points * $homeTeamWeight;
                    $totalScore += $awayTeamStats->points * $awayTeamWeight;
                } else {
                    $totalScore += $awayTeamStats->points * $awayTeamWeight;
                    $totalScore += $homeTeamStats->points * $homeTeamWeight;
                }
            }
        }

        return $totalScore / $totalWeight;
    }

    private function calculateTeamWeight($teamStats)
    {
        return pow($teamStats->wins + $teamStats->draws, 2);
    }
}
