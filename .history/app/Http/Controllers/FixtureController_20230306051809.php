<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use Inertia\Inertia;
use Inertia\Response;

class FixtureController extends Controller
{
    public function index(Fixture $fixture): Response
    {
        $fixtures = $fixture->fixturesByWeek();

        dd($fixtures);

        return Inertia::render('Fixture', [
            'fixtures' => $fixtures,
        ]);
    }
}
