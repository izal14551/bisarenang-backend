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
        Schema::create('class_session_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('class_schedules');

            // Nullable FK ke Swim_Coach (primary coach snapshot)
            $table->foreignId('primary_coach_id')
                ->nullable()
                ->constrained('swim_coaches');

            $table->date('session_date');

            // snapshot actual start/end
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->enum('session_status', ['scheduled', 'finished', 'cancelled'])
                ->default('scheduled');

            $table->unsignedInteger('actual_attendance_count')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach__session__instances');
    }
};
