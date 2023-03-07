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
     * Get games for the fixture
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

    // get min week
    public function minWeek()
    {
        return $this->min('week');
    }
}
