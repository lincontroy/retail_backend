<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            // Remove route_id as we'll use many-to-many relationship
            $table->dropForeign(['route_id']); // if foreign key exists
            $table->dropColumn('route_id');
            
            // Add new columns
            $table->string('location')->nullable()->after('address');
            $table->string('contact_phone')->nullable()->after('longitude');
            $table->string('contact_email')->nullable()->after('contact_phone');
            $table->boolean('is_active')->default(true)->after('contact_email');
            $table->text('notes')->nullable()->after('is_active');
            
            // Add indexes for better performance
            $table->index('name');
            $table->index('chain_id');
            $table->index('is_active');
            $table->index(['chain_id', 'is_active']);
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['name']);
            $table->dropIndex(['chain_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['chain_id', 'is_active']);
            $table->dropIndex(['latitude', 'longitude']);
            
            // Drop new columns
            $table->dropColumn(['location', 'contact_phone', 'contact_email', 'is_active', 'notes']);
            
            // Add back route_id
            $table->foreignId('route_id')->nullable()->after('longitude')->constrained()->onDelete('cascade');
        });
    }
};