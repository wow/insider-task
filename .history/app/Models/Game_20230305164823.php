<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'league_id',
        'home_team_id',
        'away_team_id',
        'home_team_score',
        'away_team_score',
        'week',
        'kickoff',
    ];
}
