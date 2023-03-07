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
        $standingTable = Simulation::all();

        return Inertia::render('Simulation', [
            'standingTables' => $standingTable->toArray(),
        ]);
    }

    public function simulate(Simulation $simulation): Response
    {
        $simulation->simulateAllGames();

        return Inertia::render('Simulation', [
            'data' => '',
        ]);
    }
}
