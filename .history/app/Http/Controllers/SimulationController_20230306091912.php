<?php

namespace App\Http\Controllers;

use App\Models\Simulation;
use Illuminate\Http\Request;
use Inertia\Response;

class SimulationController extends Controller
{
    //
    public function index(): Response
    {
        $teams = Simulation::all();

        return Inertia::render('Teams', [
            'teams' => $teams,
        ]);
    }
}
