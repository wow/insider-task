<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Inertia\Inertia;
use Inertia\Response;

class TeamController extends Controller
{
    // get all teams
    public function index(): Response
    {
        $teams = Team::all();

        dd($teams);

        return Inertia::render('Teams', [
            'teams' => $teams,
        ]);
    }
}
