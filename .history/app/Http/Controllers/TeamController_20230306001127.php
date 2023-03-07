<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamController extends Controller
{
    // get all teams
    public function index()
    {
        $teams = Team::all();
        // return response()->json($teams);

        return Inertia::render('Teams', [
            'teams' => $teams,
        ]);
    }
}
