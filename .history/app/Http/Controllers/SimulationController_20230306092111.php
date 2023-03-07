<?php

namespace App\Http\Controllers;

use App\Models\Simulation;
use Inertia\Inertia;
use Inertia\Response;

class SimulationController extends Controller
{
    //
    public function index(): Response
    {
        $teams = Simulation::all();

        return Inertia::render('Simulation', [
            'data' => '',
        ]);
    }
}
