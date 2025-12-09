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
        Schema::create('coach_attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('class_session_instances');

            // Coach yang benar2 datang
            $table->foreignId('coach_id')->constrained('swim_coaches');

            $table->timestamp('check_in_time');
            $table->enum('status', ['present', 'late'])->default('present');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach__attendance__logs');
    }
};
