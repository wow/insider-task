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
        $teams = Simulation::all();

        return Inertia::render('Simulation', [
            'teams' => $teams->toArray(),
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
