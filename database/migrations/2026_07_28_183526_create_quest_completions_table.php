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
        Schema::create('quest_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quest_id')->constrained('quests')->onDelete('cascade');
            $table->date('completed_on');           // the day it counts for
            $table->timestamp('completed_at');      // when you actually clicked it
            $table->unique(['quest_id', 'completed_on']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quest_completions');
    }
};
