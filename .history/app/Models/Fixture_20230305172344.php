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

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
