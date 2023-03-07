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
}
