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
        Schema::create('member_session_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('class_session_instances');
            $table->foreignId('enrollment_id')->constrained('member_course_enrollments');

            $table->timestamp('check_in_time')->nullable();

            $table->enum('status', [
                'expected',
                'booked',
                'attended',
                'absent',
                'cancelled',
            ])->default('expected');

            $table->timestamps();
            $table->softDeletes();

            // Optional: satu enrollment tidak boleh duplikat session
            $table->unique(['session_id', 'enrollment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member__session__records');
    }
};
