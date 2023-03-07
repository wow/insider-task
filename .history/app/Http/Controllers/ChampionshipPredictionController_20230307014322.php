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
            $team->simulationWeightScore = $this->calculateSimulationWeightScore($team, $remainingFixtures);
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

        foreach ($remainingFixtures as $fixture) {
            $homeTeam = $fixture->homeTeam()->first();
            $awayTeam = $fixture->awayTeam()->first();

            if ($homeTeam->id == $team->id || $awayTeam->id == $team->id) {
                $homeTeamSimulation = $homeTeam->simulation;
                $awayTeamSimulation = $awayTeam->simulation;

                $homeTeamWeight = $homeTeamSimulation->calculateSimulationWeightScore();
                $awayTeamWeight = $awayTeamSimulation->calculateSimulationWeightScore();

                $totalWeight += $homeTeamWeight + $awayTeamWeight;

                if ($homeTeam->id == $team->id) {
                    $totalScore += $homeTeamSimulation->points * $homeTeamWeight;
                    $totalScore += $awayTeamSimulation->points * $awayTeamWeight;
                } else {
                    $totalScore += $awayTeamSimulation->points * $awayTeamWeight;
                    $totalScore += $homeTeamSimulation->points * $homeTeamWeight;
                }
            }
        }

        return $totalScore / $totalWeight;
    }
}
