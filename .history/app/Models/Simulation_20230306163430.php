<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * Simulate all games for a specific week
     *
     * @param int $week
     * @return void
     */
    public function simulateWeek($week)
    {
        // Get all fixtures ordered and grouped by week
        /** @var Fixture[] $fixtures */
        $fixtures = Fixture::with(['homeTeam', 'awayTeam'])->orderBy('week')->get()->groupBy('week');

        foreach ($fixtures as $week => $weekFixtures) {
            foreach ($weekFixtures as $fixture) {
                $homeTeam = $fixture->homeTeam;
                $awayTeam = $fixture->awayTeam;

                // Calculate the winning chance of the game
                $homeTeamWeightScore = $homeTeam->teamStats->calculateTeamWeightScore();
                $awayTeamWeightScore = $awayTeam->teamStats->calculateTeamWeightScore();
                $homeTeamPlayedGames = Simulation::where('team_id', $homeTeam->id)->first();
                $awayTeamPlayedGames = Simulation::where('team_id', $awayTeam->id)->first();

                // Use the previous week's played games to calculate the winning chance
                $homeTeamWeightScore = $homeTeamWeightScore + $homeTeamPlayedGames->played;
                $homeWinningChance = $homeTeamWeightScore + $homeTeamPlayedGames->won * 0.1 - $awayTeamPlayedGames->lost * 0.1 + rand(1, 10) / 10;
                $awayWinningChance = $awayTeamWeightScore + $awayTeamPlayedGames->won * 0.1 - $homeTeamPlayedGames->lost * 0.1 + rand(1, 10) / 10;

                // Simulate the game
                $homeTeamScore = 0;
                $awayTeamScore = 0;
                if ($homeWinningChance >= $awayWinningChance) {
                    // Home team wins
                    $homeTeamScore = rand(1, 4);
                    $awayTeamScore = rand(0, $homeTeamScore - 1);
                    $homeTeamPlayedGames->played += 1;
                    $homeTeamPlayedGames->won += 1;
                    $homeTeamPlayedGames->goals_for += $homeTeamScore;
                    $homeTeamPlayedGames->goals_against += $awayTeamScore;
                    $homeTeamPlayedGames->goal_difference = $homeTeamPlayedGames->goals_for - $homeTeamPlayedGames->goals_against;
                    $homeTeamPlayedGames->points += 3;
                    $homeTeamPlayedGames->save();

                    $awayTeamPlayedGames->played += 1;
                    $awayTeamPlayedGames->lost += 1;
                    $awayTeamPlayedGames->goals_for += $awayTeamScore;
                    $awayTeamPlayedGames->goals_against += $homeTeamScore;
                    $awayTeamPlayedGames->goal_difference = $awayTeamPlayedGames->goals_for - $awayTeamPlayedGames->goals_against;
                    $awayTeamPlayedGames->save();
                } else {
                    // Away team wins
                    $awayTeamScore = rand(1, 4);
                    $homeTeamScore = rand(0, $awayTeamScore - 1);
                    $awayTeamPlayedGames->played += 1;
                    $awayTeamPlayedGames->won += 1;
                    $awayTeamPlayedGames->goals_for += $awayTeamScore;
                    $awayTeamPlayedGames->goals_against += $homeTeamScore;
                    $awayTeamPlayedGames->goal_difference = $awayTeamPlayedGames->goals_for - $awayTeamPlayedGames->goals_against;
                    $awayTeamPlayedGames->points += 3;
                    $awayTeamPlayedGames->save();

                    $homeTeamPlayedGames->played += 1;
                    $homeTeamPlayedGames->lost += 1;
                    $homeTeamPlayedGames->goals_for += $homeTeamScore;
                    $homeTeamPlayedGames->goals_against += $awayTeamScore;
                    $homeTeamPlayedGames->goal_difference = $homeTeamPlayedGames->goals_for - $homeTeamPlayedGames->goals_against;
                    $homeTeamPlayedGames->save();
                }



            }



            $homeTeamStats = $weekFixtures->homeTeam->teamStats;
            $awayTeamStats = $weekFixtures->awayTeam->teamStats;

            // Calculate score for home and away teams
            $homeTeamScore = $homeTeamStats->calculateTeamWeightScore();
            $awayTeamScore = $awayTeamStats->calculateTeamWeightScore();

            // Add a little bit of chance factor for less powerful team
            if ($homeTeamScore < $awayTeamScore) {
                $homeTeamScore = rand($homeTeamScore - 1, $awayTeamScore);
            } elseif ($awayTeamScore < $homeTeamScore) {
                $awayTeamScore = rand($awayTeamScore - 1, $homeTeamScore);
            }

            // Determine the winner or if it's a draw
            if ($homeTeamScore > $awayTeamScore) {
                $winner = 'home';
                $loser = 'away';
            } elseif ($homeTeamScore < $awayTeamScore) {
                $winner = 'away';
                $loser = 'home';
            } else {
                $winner = 'draw';
                $loser = 'draw';
            }

            // Update simulation stats for each team
            $homeTeamStats->played++;
            $awayTeamStats->played++;
            $homeTeamStats->goals_for += $homeTeamScore;
            $awayTeamStats->goals_for += $awayTeamScore;
            $homeTeamStats->goals_against += $awayTeamScore;
            $awayTeamStats->goals_against += $homeTeamScore;

            if ($winner == 'home') {
                $homeTeamStats->won++;
                $homeTeamStats->points += 3;
                $awayTeamStats->lost++;
            } elseif ($winner == 'away') {
                $awayTeamStats->won++;
                $awayTeamStats->points += 3;
                $homeTeamStats->lost++;
            } else {
                $homeTeamStats->drawn++;
                $awayTeamStats->drawn++;
                $homeTeamStats->points++;
                $awayTeamStats->points++;
            }

            // Save the changes to the team stats
            $homeTeamStats->save();
            $awayTeamStats->save();
        }
    }

    function simulateAllWeeks()
    {
        // Loop through all fixtures
        foreach (Fixture::all() as $fixture) {
            $homeTeamStats = $fixture->homeTeam->teamStats;
            $awayTeamStats = $fixture->awayTeam->teamStats;

            // Calculate score for home and away teams
            $homeTeamWeight = $homeTeamStats->calculateTeamWeightScore();
            $awayTeamWeight = $awayTeamStats->calculateTeamWeightScore();

            // Calculate probability of a home win, away win and draw
            $homeWinProb = $homeTeamWeight / ($homeTeamWeight + $awayTeamWeight);
            $awayWinProb = $awayTeamWeight / ($homeTeamWeight + $awayTeamWeight);
            $drawProb = 1 - $homeWinProb - $awayWinProb;

            // Determine the winner or if it's a draw based on the probabilities
            $result = rand(0, 100) / 100;
            if ($result < $homeWinProb) {
                $winner = 'home';
                $loser = 'away';
            } elseif ($result < $homeWinProb + $awayWinProb) {
                $winner = 'away';
                $loser = 'home';
            } else {
                $winner = 'draw';
                $loser = 'draw';
            }

            // Update simulation stats for each team
            $homeTeamStats->played++;
            $awayTeamStats->played++;
            $homeTeamStats->goals_for += $fixture->home_team_score;
            $awayTeamStats->goals_for += $fixture->away_team_score;
            $homeTeamStats->goals_against += $fixture->away_team_score;
            $awayTeamStats->goals_against += $fixture->home_team_score;

            if ($winner == 'home') {
                $homeTeamStats->won++;
                $homeTeamStats->points += 3;
                $awayTeamStats->lost++;
            } elseif ($winner == 'away') {
                $awayTeamStats->won++;
                $awayTeamStats->points += 3;
                $homeTeamStats->lost++;
            } else {
                $homeTeamStats->drawn++;
                $awayTeamStats->drawn++;
                $homeTeamStats->points++;
                $awayTeamStats->points++;
            }

            // Save the changes to the team stats
            $homeTeamStats->save();
            $awayTeamStats->save();
        }
    }

    public function simulateAllGames()
    {
        $fixtures = Fixture::with(['homeTeam', 'awayTeam'])->orderBy('week')->get();

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

            for ($i = 1; $i <= 10; $i++) {
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
                'played' => true,
            ]);

            // $homeTeam->teamStats()->update([
            //     'matches_played' => $homeTeamPlayedGames + 1,
            //     'goals_for' => $homeTeam->teamStats->goals_for + $homeTeamScore,
            //     'goals_against' => $homeTeam->teamStats->goals_against + $awayTeamScore,
            //     'goal_difference' => $homeTeam->teamStats->goals_for - $homeTeam->teamStats->goals_against,
            //     'points' => $homeTeam->teamStats->points + ($homeTeamScore > $awayTeamScore ? 3 : ($homeTeamScore == $awayTeamScore ? 1 : 0)),
            // ]);

            // $awayTeam->teamStats()->update([
            //     'matches_played' => $awayTeamPlayedGames + 1,
            //     'goals_for' => $awayTeam->teamStats->goals_for + $awayTeamScore,
            //     'goals_against' => $awayTeam->teamStats->goals_against + $homeTeamScore,
            //     'goal_difference' => $awayTeam->teamStats->goals_for - $awayTeam->teamStats->goals_against,
            //     'points' => $awayTeam->teamStats->points + ($awayTeamScore > $homeTeamScore ? 3 : ($awayTeamScore == $homeTeamScore ? 1 : 0)),
            // ]);
        }
    }


}
