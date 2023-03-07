<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    // get all teams
    public function index()
    {
        $teams = Team::all();
        return response()->json($teams);
    }
}
