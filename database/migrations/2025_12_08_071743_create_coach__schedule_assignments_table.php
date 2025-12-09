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
        Schema::create('coach_schedule_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained('swim_coaches');
            $table->foreignId('schedule_id')->constrained('class_schedules');

            $table->boolean('is_primary')->default(false);
            $table->date('effective_from');
            $table->date('effective_until')->nullable(); // NULL = masih aktif

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach__schedule__assignments');
    }
};
