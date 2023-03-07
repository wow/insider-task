<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Simulation;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SimulationController extends Controller
{
    /**
     * Display Simulation page.
     */
    public function index(Fixture $fixture): Response
    {
        $standingTables = Simulation::all();

        foreach ($standingTables as $id => $standingTable) {
            $standingTables[$id]['team'] = $standingTable->team;
        }

        $fixtures = $fixture->fixturesByWeek();

        foreach ($fixtures as $week => $fixture) {
            foreach ($fixture as $key => $value) {
                $fixtures[$week][$key]['home_team'] = $value->homeTeam;
                $fixtures[$week][$key]['away_team'] = $value->awayTeam;
            }
        }

        return Inertia::render('Simulation', [
            'standingTables' => $standingTables,
            'fixtures' => $fixtures,
        ]);
    }

    public function simulate(Simulation $simulation): RedirectResponse
    {
        $simulation->simulateAllGames();

        return to_route('simulations.index');
    }
}
