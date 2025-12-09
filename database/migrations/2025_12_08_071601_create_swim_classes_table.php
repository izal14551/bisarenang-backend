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
        Schema::create('swim_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pool_id')->constrained('pool_locations');
            $table->string('name');
            $table->text('description')->nullable();

            // per week / flexible
            $table->enum('schedule_type', ['per_week', 'flexible']);

            $table->integer('max_capacity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swim_class');
    }
};
