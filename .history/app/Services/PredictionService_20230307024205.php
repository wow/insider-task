<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Simulation;

class PredictionService
{
    /**
     * Calculate championship prediction for each teamü
     * get current standings from the simulated data
     * get calculated simulation weight score for each team
     *
     *
     * @return Collection
     */
    public function predict()
    {
        // get current standings from the simulated data
        $simulations = $this->getCurrentStandings();

        // get remaining fixtures and calculate the simulation weight score for each team
        $remainingFixtures = Fixture::where('week', '>', $this->getCurrentWeek())->get();

        /**
         * @var Simulation $simulation
         */
        foreach ($simulations as $simulation) {
            $simulation->simulationWeightScore = $simulation->calculateSimulationWeightScore();
        }

        // Calculate championship prediction for each team
        $totalPoints = $simulations->sum('points');
        $simulations->each(function ($team) use ($totalPoints) {
            $team->championshipPrediction = $team->points / $totalPoints;
        });

        return $simulations;
    }

    public function getCurrentStandings()
    {
        $standings = Simulation::with('team')->orderBy('points', 'desc')->orderBy('goal_difference', 'desc')->get();

        return $standings;
    }

    public function getCurrentWeek()
    {
        $currentWeek = Simulation::max('played');

        return $currentWeek;
    }


}
