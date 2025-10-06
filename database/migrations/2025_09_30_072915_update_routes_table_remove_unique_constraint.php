<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            // Remove the unique constraint
            $table->dropUnique(['user_id', 'day_id']);
            
            // Add new columns for better route management
            $table->time('start_time')->nullable()->after('description');
            $table->time('end_time')->nullable()->after('start_time');
            $table->string('area')->nullable()->after('end_time');
            $table->integer('priority')->default(0)->after('area');
            
            // Add new indexes
            $table->index(['user_id', 'day_id', 'start_time']);
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'day_id', 'start_time']);
            $table->dropIndex(['priority']);
            
            $table->dropColumn(['start_time', 'end_time', 'area', 'priority']);
            
            // Restore unique constraint
            $table->unique(['user_id', 'day_id']);
        });
    }
};