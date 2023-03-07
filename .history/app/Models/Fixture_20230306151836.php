<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->with(['homeTeam', 'awayTeam'])
            ->orderBy('week')
            ->get()
            ->groupBy('week');
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


    // add game to the fixture
    public function addGame()
    {
        $this->games()->create([
            'played' => false,
        ]);
    }

    /**
     * Generate fixtures for the teams
     *
     * @return void
     */
    public function generateFixturesForTeams()
    {
        // Clear all fixtures
        $this->truncate();

        $teams = Team::all();
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
            'home_team_score' => 0,
            'away_team_score' => 0,
            'home_team_weight_score' => 0,
            'away_team_weight_score' => 0,
            'played' => false,
        ]);

        $secondFixture = $this->create([
            'home_team_id' => $awayTeam->id,
            'away_team_id' => $homeTeam->id,
            'week' => $round + $rounds + 1
        ]);

        Game::create([
            'fixture_id' => $secondFixture->id,
            'home_team_score' => 0,
            'away_team_score' => 0,
            'home_team_weight_score' => 0,
            'away_team_weight_score' => 0,
            'played' => false,
        ]);
    }
}
