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
        Schema::create('module_completions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('module_id')->constrained();
            $table->foreignId('module_part_id')->constrained();
            $table->foreignId('student_id')->constrained('users');
            $table->timestamp('completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_completions');
    }
};
