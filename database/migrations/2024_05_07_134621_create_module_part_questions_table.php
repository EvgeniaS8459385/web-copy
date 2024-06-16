<?php

use App\Models\Module\ModulePartQuestion;
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
        Schema::create('module_part_questions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('text');
            $table->integer('order');
            $table->enum('type', [
                ModulePartQuestion::TYPE_SINGLE_CHOICE,
            ]);
            $table->foreignId('module_part_id')->constrained('module_parts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_part_questions');
    }
};
