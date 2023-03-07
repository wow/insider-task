<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'fixture_id',
        'game_id',
        'points',
        'created_at',
        'updated_at',
    ];
}
