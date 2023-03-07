<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Game;
use App\Models\Simulation;
use App\Services\PredictionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class SimulationController extends Controller
{
    /**
     * Display Simulation page.
     */
    public function index(Simulation $simulation, Fixture $fixture, PredictionService $predictionService): Response
    {
        // get current unplayed week
        $currentWeek = $simulation->getCurrentUnplayedWeek();

        $standingTables = $simulation->all();
        foreach ($standingTables as $id => $standingTable) {
            $standingTables[$id]['team'] = $standingTable->team;
        }

        $fixtures = $fixture->fixturesByWeek();
        foreach ($fixtures as $week => $fixture) {
            foreach ($fixture as $key => $value) {
                $fixtures[$week][$key]['home_team'] = $value->homeTeam;
                $fixtures[$week][$key]['away_team'] = $value->awayTeam;
                $fixtures[$week][$key]['game'] = $value->game;
            }
        }

        $predictions = $predictionService->predictChampionship();

        return Inertia::render('Simulation', [
            'standingTables' => $standingTables,
            'fixtures' => $fixtures,
            'currentWeek' => $currentWeek,
            'predictions' => $predictions,
        ]);
    }

    public function simulate(Simulation $simulation): RedirectResponse
    {
        $simulation->simulateAllGames();

        return to_route('simulations.index');
    }

    public function simulateCurrent(Simulation $simulation): RedirectResponse
    {
        $simulation->simulateByWeek($simulation->getCurrentUnplayedWeek());

        return to_route('simulations.index');
    }

    public function reset(): RedirectResponse
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Game::truncate();
        Simulation::truncate();
        Fixture::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Artisan::call('db:seed', ['--class' => 'SimulationSeeder']);

        return to_route('simulations.index');
    }
}
