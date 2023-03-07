<?php

namespace App\Http\Controllers;

use App\Models\Simulation;
use Inertia\Inertia;
use Inertia\Response;

class SimulationController extends Controller
{
    /**
     * Display Simulation page.
     */
    public function index(): Response
    {
        $standingTables = Simulation::all();

        foreach ($standingTables as $id => $standingTable) {
            $standingTables[$id]['team'] = $standingTable->team;
        }

        return Inertia::render('Simulation', [
            'standingTables' => $standingTables,
        ]);
    }

    public function simulate(Simulation $simulation)
    {
        $simulation->simulateAllGames();

        return to_route('simulations.index');
    }
}
