<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = [
        'league_id',
        'game_id',
        'home_team_id',
        'away_team_id',
        'kickoff',
        'week',
    ];

    // get the league
    public function league()
    {
        return $this->belongsTo(League::class);
    }

    // get all games
    public function games()
    {
        return $this->hasMany(Game::class);
    }

    // get all games for the fixture by week
    public function gamesByWeek($week)
    {
        return $this->hasMany(Game::class)->where('week', $week);
    }

    // get all games for the fixture by week and league
    public function gamesByWeekAndLeague($week, $league)
    {
        return $this->hasMany(Game::class)
            ->where('week', $week)
            ->where('league_id', $league)
        ;
    }

    // get the game for the fixture
    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    // get the game for the fixture
    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }
}
