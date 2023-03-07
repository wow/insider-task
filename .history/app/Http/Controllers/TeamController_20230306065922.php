<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TeamController extends Controller
{
    // get all teams
    public function index(): Response
    {
        $teams = Team::all();

        return Inertia::render('Teams', [
            'teams' => $teams,
        ]);
    }

    // Generate fixtures for teams and redirect to fixtures page
    public function generateFixtures(Team $team)
    {
        $team->generateFixtures();

        return to_route('home');
    }

}
