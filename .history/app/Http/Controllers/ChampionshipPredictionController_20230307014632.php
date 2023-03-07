<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Simulation;

class ChampionshipPredictionController extends Controller
{
    public function predict()
    {
        $simulator = new Simulation();

        // Get current standings from the simulated data
        $simulations = $simulator->getCurrentStandings();

        // Get remaining fixtures and calculate the simulation weight score for each team
        $remainingFixtures = Fixture::where('week', '>', $simulator->getCurrentWeek())->get();
        foreach ($simulations as $simulation) {
            $simulation->simulationWeightScore = $this->calculateSimulationWeightScore($simulation, $remainingFixtures);
        }

        // Calculate championship prediction for each team
        $totalPoints = $simulations->sum('points');
        $simulations->each(function ($team) use ($totalPoints) {
            $team->championshipPrediction = $team->points / $totalPoints;
        });

        return $simulations;
    }

    public function calculateSimulationWeightScore($simulation, $remainingFixtures)
    {
        $totalScore = 0;
        $totalWeight = 0;

        foreach ($remainingFixtures as $fixture) {
            $homeTeam = $fixture->homeTeam()->first();
            $awayTeam = $fixture->awayTeam()->first();

            dd($homeTeam, $awayTeam);

            if ($homeTeam->id == $simulation->team_id || $awayTeam->id == $simulation->team_id) {
                $homeTeamSimulation = $homeTeam->simulation;
                $awayTeamSimulation = $awayTeam->simulation;

                $homeTeamWeight = $homeTeamSimulation->calculateSimulationWeightScore();
                $awayTeamWeight = $awayTeamSimulation->calculateSimulationWeightScore();

                $totalWeight += $homeTeamWeight + $awayTeamWeight;

                if ($homeTeam->id == $simulation->team_id) {
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
