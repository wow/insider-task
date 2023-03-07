<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerRecord extends Model
{
    use HasFactory;

    // Goals weight points constant
    const GOALS_WEIGHT = 5;
    // Assists weight points constant
    const ASSISTS_WEIGHT = 3;
    // Clean sheets weight points constant
    const CLEAN_SHEETS_WEIGHT = 4;


    protected $fillable = [
        'player_id',
        'appearances',
        'clean_sheets',
        'goals',
        'assists',
        'created_at',
        'updated_at',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function getWeightedPoints()
    {
        return
            $this->goals * self::GOALS_WEIGHT +
            $this->assists * self::ASSISTS_WEIGHT +
            $this->clean_sheets * self::CLEAN_SHEETS_WEIGHT;
    }

    public function getWeightedPointsPerGameAttribute()
    {
        return $this->getWeightedPoints() / $this->appearances;
    }

    public function getWeightedPointsPer90Attribute()
    {
        return $this->getWeightedPoints() / $this->appearances * 90;
    }

    public function getWeightedPointsPer90PerGameAttribute()
    {
        return $this->getWeightedPointsPer90Attribute() / $this->appearances;
    }
}
