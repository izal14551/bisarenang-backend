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
        Schema::table('swim_coaches', function (Blueprint $table) {
            $table->dropForeign(['pool_id']);
            $table->dropColumn('pool_id');
        });
    }

    public function down(): void
    {
        Schema::table('swim_coaches', function (Blueprint $table) {
            $table->foreignId('pool_id')->constrained('pool_locations');
        });
    }
};
