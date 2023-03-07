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
        Schema::create('player_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained();
            $table->integer('appearances');
            $table->integer('clean_sheets');
            $table->integer('wins');
            $table->integer('losses');


            $table->integer('goals');
            $table->integer('assists');
            $table->integer('yellow_cards');
            $table->integer('red_cards');
            $table->integer('own_goals');
            $table->integer('penalties_saved');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_records');
    }
};
