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
        Schema::create('member_course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('swim_members');
            $table->foreignId('class_id')->constrained('swim_classes');
            $table->foreignId('schedule_id')->nullable()->constrained('class_schedules');

            $table->enum('status', ['active', 'cancelled'])->default('active');
            $table->date('enrollment_date');
            $table->date('cancellation_date')->nullable();

            // optional: satu member hanya boleh 1 enrollment unik per class+schedule
            $table->unique(['member_id', 'class_id', 'schedule_id']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member__course__enrollments');
    }
};
