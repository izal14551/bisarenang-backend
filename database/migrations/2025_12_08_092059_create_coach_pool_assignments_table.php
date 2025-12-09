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
        Schema::create('coach_pool_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('coach_id')->constrained('swim_coaches')->onDelete('cascade');
            $table->foreignId('pool_id')->constrained('pool_locations')->onDelete('cascade');

            $table->date('effective_from')->default(now());
            $table->date('effective_until')->nullable();

            $table->boolean('is_primary')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach_pool_assignments');
    }
};
