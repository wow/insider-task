<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'week',
    ];

    // Get all fixtures ordered and grouped by week, with relationship to teams
    public function fixturesByWeek()
    {
        return $this->with(['homeTeam', 'awayTeam', 'game'])
            ->orderBy('week')
            ->get()
            ->groupBy('week');
    }

    // get single game for the fixture
    public function game()
    {
        return $this->hasOne(Game::class);
    }

    /**
     * Get all fixtures ordered and grouped by week, with relationship to teams
     *
     * @return Game[]|Collection
     */
    public function games()
    {
        return $this->hasMany(Game::class);
    }

    // get all games for the fixture by week
    public function gamesByWeek($week)
    {
        return $this->hasMany(Game::class)->where('week', $week);
    }

    // get homeTeam for the fixture
    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    // get awayTeam for the fixture
    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    // get current unplayed week
    public function currentWeek()
    {
        return $this->where('week', '>', 0)
            ->where('week', '<=', $this->max('week'))
            ->whereDoesntHave('game', function ($query) {
                $query->where('played', true);
            })
            ->min('week')
        ;
    }

    /**
     * Generate fixtures for the teams
     *
     * @return void
     */
    public function generateFixturesForTeams()
    {
        $this->resetDataForNewFixtures();

        $teams = Team::all();
        $teams = $teams->shuffle();
        $teamCount = $teams->count();

        $rounds = $teamCount - 1;
        $matchesPerRound = $teamCount / 2;

        for ($round = 0; $round < $rounds; $round++) {
            for ($match = 0; $match < $matchesPerRound; $match++) {
                $homeTeamIndex = ($round + $match) % ($teamCount - 1);
                $awayTeamIndex = ($teamCount - 1 - $match + $round) % ($teamCount - 1);

                // Last team stays in the same position while the others rotate
                if ($match == 0) {
                    $awayTeamIndex = $teamCount - 1;
                }

                $homeTeam = $teams[$homeTeamIndex];
                $awayTeam = $teams[$awayTeamIndex];

                // Generate fixtures for the home and away teams
                $this->createFixtures($homeTeam, $awayTeam, $round, $rounds);
            }
        }
    }

    // create fixtures for the home and away teams
    private function createFixtures($homeTeam, $awayTeam, $round, $rounds)
    {
        // Generate fixtures for the home and away teams
        $firstFixture = $this->create([
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'week' => $round + 1
        ]);

        Game::create([
            'fixture_id' => $firstFixture->id,
        ]);

        $secondFixture = $this->create([
            'home_team_id' => $awayTeam->id,
            'away_team_id' => $homeTeam->id,
            'week' => $round + $rounds + 1
        ]);

        Game::create([
            'fixture_id' => $secondFixture->id,
        ]);
    }

    /**
     * Reset all fixtures, games and team stats
     *
     * @return void
     */
    private function resetDataForNewFixtures()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->truncate();
        Game::truncate();
        TeamStats::truncate();
        Simulation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Artisan::call('db:seed', ['--class' => 'TeamStatsSeeder', '--class' => 'SimulationSeeder']);
    }
}
