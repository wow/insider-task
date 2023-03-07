<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('player_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained();
            $table->integer('appearances');
            $table->integer('wins');
            $table->integer('losses');
            // Goalkeeping
            $table->integer('saves');
            $table->integer('penalties_saved');
            $table->integer('punches');
            $table->integer('high_claims');
            $table->integer('catches');
            $table->integer('sweeper_clearances');
            $table->integer('throw_outs');
            $table->integer('goal_kicks');
            // Defence
            $table->integer('clean_sheets');
            $table->integer('goals_conceded');
            $table->integer('tackles');
            $table->integer('tackle_success');
            $table->integer('blocked_shots');
            $table->integer('interceptions');
            $table->integer('clearances');
            $table->integer('recoveries');
            $table->integer('duels_won');
            $table->integer('duels_lost');
            $table->integer('own_goals');
            $table->integer('errors_leading_to_goal');
            // Attack
            $table->integer('goals');
            $table->integer('headed_goals');
            $table->integer('penalties_scored');
            $table->integer('free_kicks_scored');
            $table->integer('shots');
            // Team Play
            $table->integer('assists');
            $table->integer('passes');
            $table->decimal('passes_per_match', 6, 2);
            $table->integer('big_chances_created');
            $table->integer('crosses');
            $table->integer('cross_accuracy');
            $table->integer('through_balls');
            $table->integer('accurate_long_balls');
            // Discipline
            $table->integer('yellow_cards');
            $table->integer('red_cards');
            $table->integer('fouls');
            $table->integer('offsides');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_stats');
    }
};
