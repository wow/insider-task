<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Termwind\Components\Dd;

class Simulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'played',
        'won',
        'drawn',
        'lost',
        'goals_for',
        'goals_against',
        'goal_difference',
        'points',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function simulateAllGames()
    {
        $this->resetSimulation();

        $fixturesByWeek = Fixture::with(['homeTeam', 'awayTeam'])->orderBy('week')->get()->groupBy('week');
        // $fixturesByWeek = Fixture::with(['homeTeam', 'awayTeam', 'games'])->fixturesByWeek();
        foreach ($fixturesByWeek as $week => $fixtures) {
            foreach ($fixtures as $fixture) {
                $homeTeam = $fixture->homeTeam;
                $awayTeam = $fixture->awayTeam;

                $homeTeamWeightScore = $homeTeam->teamStats->calculateTeamWeightScore();
                $awayTeamWeightScore = $awayTeam->teamStats->calculateTeamWeightScore();

                $homeTeamPlayedGames = $homeTeam->teamStats->matches_played;
                $awayTeamPlayedGames = $awayTeam->teamStats->matches_played;

                // Calculate the winning chance for each team based on their weight score and previous games played
                $homeTeamWinChance = ($homeTeamWeightScore + $homeTeamPlayedGames) / ($homeTeamWeightScore + $awayTeamWeightScore + $homeTeamPlayedGames + $awayTeamPlayedGames);
                $awayTeamWinChance = ($awayTeamWeightScore + $awayTeamPlayedGames) / ($homeTeamWeightScore + $awayTeamWeightScore + $homeTeamPlayedGames + $awayTeamPlayedGames);

                // Add a little bit of chance factor for less powerful teams
                if ($homeTeamWeightScore < $awayTeamWeightScore) {
                    $homeTeamWinChance += 0.1;
                } elseif ($homeTeamWeightScore > $awayTeamWeightScore) {
                    $awayTeamWinChance += 0.1;
                }

                // Simulate the game and update the scores and stats for each team
                $homeTeamScore = 0;
                $awayTeamScore = 0;

                for ($i = 1; $i <= rand(1, 10); $i++) {
                    $randomNumber = mt_rand(1, 100);

                    if ($randomNumber <= ($homeTeamWinChance * 100)) {
                        $homeTeamScore++;
                    } else {
                        $awayTeamScore++;
                    }
                }

                $game = $fixture->games()->first();
                $game->update([
                    'home_team_score' => $homeTeamScore,
                    'away_team_score' => $awayTeamScore,
                    'home_team_weight_score' => $homeTeamWeightScore,
                    'away_team_weight_score' => $awayTeamWeightScore,
                    'played' => true,
                ]);

                // Update simulation table for each team
                $homeTeamSimulation = $homeTeam->simulation;
                $awayTeamSimulation = $awayTeam->simulation;




                $homeTeamSimulation->played++;
                $awayTeamSimulation->played++;

                dd($homeTeamSimulation);

                if ($homeTeamScore > $awayTeamScore) {
                    $homeTeamSimulation->won++;
                    $homeTeamSimulation->points += 3;
                    $awayTeamSimulation->lost++;
                } elseif ($homeTeamScore < $awayTeamScore) {
                    $awayTeamSimulation->won++;
                    $awayTeamSimulation->points += 3;
                    $homeTeamSimulation->lost++;
                } else {
                    $homeTeamSimulation->drawn++;
                    $homeTeamSimulation->points++;
                    $awayTeamSimulation->drawn++;
                    $awayTeamSimulation->points++;
                }

                $homeTeamSimulation->goals_for += $homeTeamScore;
                $homeTeamSimulation->goals_against += $awayTeamScore;
                $homeTeamSimulation->goal_difference = $homeTeamSimulation->goals_for - $homeTeamSimulation->goals_against;

                $awayTeamSimulation->goals_for += $awayTeamScore;
                $awayTeamSimulation->goals_against += $homeTeamScore;
                $awayTeamSimulation->goal_difference = $awayTeamSimulation->goals_for - $awayTeamSimulation->goals_against;

                $homeTeamSimulation->save();
                $awayTeamSimulation->save();
            }
        }
    }

    private function resetSimulation()
    {
        $teams = Team::all();

        foreach ($teams as $team) {
            $team->simulation()->update([
                'played' => 0,
                'won' => 0,
                'drawn' => 0,
                'lost' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'points' => 0,
            ]);
        }
    }

    /**
     * Simulate the all games in the Fixtures by week.
     * This function considers the teams weight from TeamStats()->calculateTeamWeightScore(),
     * adding their past weeks played games to the calculation from Simulations table for
     * a winning chance of the current game and a little bit of a chance factor for less powerful team.
     *
     * It updates the games, simulation table, and team stats.
     *
     * @return void
     */
    public function SimulateFixtures()
    {
        // Get all fixtures ordered and grouped by week
        $fixturesByWeek = Fixture::with(['homeTeam', 'awayTeam', 'games'])->fixturesByWeek();

        // Loop through all fixtures
        foreach ($fixturesByWeek as $week => $fixtures) {
            foreach ($fixtures as $fixture) {
                $homeTeam = $fixture->homeTeam;
                $awayTeam = $fixture->awayTeam;

                // Calculate the team weight scores
                $homeTeamWeight = $homeTeam->teamStats->calculateTeamWeightScore();
                $awayTeamWeight = $awayTeam->teamStats->calculateTeamWeightScore();

                // Calculate the winning chances of each team by their past played games
                $homeTeamPastGames = $fixture->games->where('home_team_id', $homeTeam->id);
                $awayTeamPastGames = $fixture->games->where('away_team_id', $awayTeam->id);
                $homeTeamWinningChance = Simulation::calculateWinningChance($homeTeamPastGames, $awayTeamWeight);
                $awayTeamWinningChance = Simulation::calculateWinningChance($awayTeamPastGames, $homeTeamWeight);

                // Add a little chance factor to the home team
                $homeTeamWinningChance += 0.05;

                // Add a little chance factor to the less powerful team
                if ($homeTeamWeight > $awayTeamWeight) {
                    $awayTeamWinningChance -= 0.04;
                } else {
                    $homeTeamWinningChance -= 0.04;
                }

                // Generate a random number to determine the winner
                $randomNumber = mt_rand(1, 100) / 100;

                // Update the game
                $game = $fixture->games->first();
                $game->home_team_score = $randomNumber <= $homeTeamWinningChance ? mt_rand(0, 5) : 0;
                $game->away_team_score = $randomNumber <= $awayTeamWinningChance ? mt_rand(0, 5) : 0;
                $game->save();

                // Update the simulation table
                Simulation::updateSimulationTable($homeTeam, $awayTeam, $game->home_team_score, $game->away_team_score);

                // Update team stats
                TeamStats::updateTeamStats($homeTeam, $game->home_team_score, $game->away_team_score);
                TeamStats::updateTeamStats($awayTeam, $game->away_team_score, $game->home_team_score);
            }
        }
    }



}
