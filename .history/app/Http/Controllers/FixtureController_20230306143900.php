<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FixtureController extends Controller
{
    /**
     * Display Fixture page.
     * @param Fixture $fixture
     * @return Response
     * @throws \Exception
     */
    public function index(Fixture $fixture): Response
    {
        $fixtures = $fixture->fixturesByWeek();

        foreach ($fixtures as $week => $fixture) {
            foreach ($fixture as $key => $value) {
                $fixtures[$week][$key]['home_team'] = $value->homeTeam;
                $fixtures[$week][$key]['away_team'] = $value->awayTeam;
            }
        }

        return Inertia::render('Fixture', [
            'fixtures' => $fixtures,
        ]);
    }

    /**
     * Generate fixtures for all teams.
     * @param Team $team
     * @return RedirectResponse
     */
    public function generateFixtures(Team $team): RedirectResponse
    {
        $team->generateFixtures();

        return to_route('fixtures.index');
    }
}
