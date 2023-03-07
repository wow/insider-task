<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
