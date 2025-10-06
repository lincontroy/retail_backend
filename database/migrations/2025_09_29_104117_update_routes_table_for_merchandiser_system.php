<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            // Add new columns
            $table->foreignId('day_id')->after('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name')->nullable()->change(); // Make name nullable if it wasn't
            $table->text('description')->nullable()->after('notes');
            $table->integer('estimated_duration')->nullable()->after('description')->comment('in minutes');
            $table->decimal('estimated_distance', 8, 2)->nullable()->after('estimated_duration')->comment('in km');
            $table->boolean('is_active')->default(true)->after('estimated_distance');
            
            // Add indexes
            $table->index('day_id');
            $table->index('is_active');
            $table->index(['user_id', 'day_id']);
            $table->index(['day_id', 'is_active']);
            
            // Add unique constraint to prevent duplicate routes per user per day
            $table->unique(['user_id', 'day_id']);
        });
    }

    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            // Drop unique constraint first
            $table->dropUnique(['user_id', 'day_id']);
            
            // Drop indexes
            $table->dropIndex(['day_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['user_id', 'day_id']);
            $table->dropIndex(['day_id', 'is_active']);
            
            // Drop columns
            $table->dropColumn(['day_id', 'description', 'estimated_duration', 'estimated_distance', 'is_active']);
        });
    }
};