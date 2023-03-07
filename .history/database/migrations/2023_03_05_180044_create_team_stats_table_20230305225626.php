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
        Schema::create('team_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained();
            $table->integer('matches_played');
            $table->integer('wins');
            $table->integer('losses');
            // Goalkeeping
            $table->integer('saves');
            $table->integer('penalties_saved');
            $table->integer('punches');
            $table->integer('high_claims');
            $table->integer('catches');
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
            $table->integer('own_goals');
            // Attack
            $table->integer('goals');
            $table->integer('penalties_scored');
            $table->integer('shots');
            $table->integer('big_chances_created');
            $table->integer('hit_woodwork');
            // Team Play
            $table->integer('passes');
            $table->integer('passes_accuracy');
            $table->integer('crosses');
            $table->integer('cross_accuracy');
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
