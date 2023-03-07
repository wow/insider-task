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

        return Inertia::render('Teams', [
            'teams' => $teams,
        ]);
    }

    // Generate fixtures for teams and redirect to fixtures page
    public function generateFixtures(Team $team): Response
    {
        $team->generateFixtures();

        return Inertia::render('Fixtures', [
            'fixtures' => Fixture::all(),
        ]);
    }

}
