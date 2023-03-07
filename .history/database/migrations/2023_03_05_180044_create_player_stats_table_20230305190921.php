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
            $table->string('topic');
            // Goalkeeping
            $table->integer('saves');
            $table->integer('penalties_saved');
            $table->integer('punches');
            $table->integer('high_claims');
            $table->integer('catches');
            $table->integer('sweeper_clearances');
            $table->integer('throw_outs');
            $table->integer('goals_kicks');
            // Defence
            $table->integer('tackles');
            $table->integer('clean_sheets');

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
