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

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    // get games
    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }
}
