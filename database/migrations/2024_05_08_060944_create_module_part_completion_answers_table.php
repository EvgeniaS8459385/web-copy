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
        Schema::create('module_part_completion_answers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('module_part_completion_id')->constrained('module_part_completions');
            $table->foreignId('module_part_question_id')->constrained();
            $table->foreignId('module_part_question_answer_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_part_completion_answers');
    }
};
