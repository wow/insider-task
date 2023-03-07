<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerRecord extends Model
{
    use HasFactory;

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

    public function getPointsAttribute()
    {
        return $this->goals * 5 + $this->assists * 3 + $this->clean_sheets * 4;
    }

    public function getWeightedPointsAttribute()
    {
        return $this->goals * 5 + $this->assists * 3 + $this->clean_sheets * 4;
    }

    public function getWeightedPointsPerGameAttribute()
    {
        return $this->weighted_points / $this->appearances;
    }

    public function getPointsPerGameAttribute()
    {
        return $this->points / $this->appearances;
    }

    public function getPointsPer90Attribute()
    {
        return $this->points / $this->appearances * 90;
    }

    public function getWeightedPointsPer90Attribute()
    {
        return $this->weighted_points / $this->appearances * 90;
    }

    public function getPointsPer90PerGameAttribute()
    {
        return $this->points_per_90 / $this->appearances;
    }

    public function getWeightedPointsPer90PerGameAttribute()
    {
        return $this->weighted_points_per_90 / $this->appearances;
    }

    public function getPointsPer90PerGamePerMinuteAttribute()
    {
        return $this->points_per_90_per_game / 90;
    }

    public function getWeightedPointsPer90PerGamePerMinuteAttribute()
    {
        return $this->weighted_points_per_90_per_game / 90;
    }

    public function getPointsPer90PerMinuteAttribute()
    {
        return $this->points_per_90 / 90;
    }

    public function getWeightedPointsPer90PerMinuteAttribute()
    {
        return $this->weighted_points_per_90 / 90;
    }

    public function getPointsPerGamePerMinuteAttribute()
    {
        return $this->points_per_game / 90;
    }

    public function getWeightedPointsPerGamePerMinuteAttribute()
    {
        return $this->weighted_points_per_game / 90;
    }

    public function getPointsPerMinuteAttribute()
    {
        return $this->points / 90;
    }

    public function getWeightedPointsPerMinuteAttribute()
    {
        return $this->weighted_points / 90;
    }

    public function getPointsPer90PerGamePerMinutePerSecondAttribute()
    {
        return $this->points_per_90_per_game_per_minute / 60;
    }

    public function getWeightedPointsPer90PerGamePerMinutePerSecondAttribute()
    {
        return $this->weighted_points_per_90_per_game_per_minute / 60;
    }
}
