<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use Inertia\Inertia;
use Inertia\Response;

class FixtureController extends Controller
{
    public function index(): Response
    {
        $teams = Fixture::all();

        return Inertia::render('Fixture', [
            'teams' => $teams,
        ]);
    }
}
