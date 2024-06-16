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
        Schema::create('module_part_completions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('module_id')->constrained();
            $table->foreignId('module_part_id')->constrained();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('report_id')->nullable()->constrained('module_part_completion_reports');
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('theory_completed_at')->nullable();
            $table->timestamp('test_started_at')->nullable();
            $table->timestamp('test_completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_part_completions');
    }
};
