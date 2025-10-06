<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('route_shop', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->integer('order')->default(0); // Sequence in the route
            $table->time('estimated_arrival')->nullable();
            $table->time('estimated_departure')->nullable();
            $table->integer('duration_minutes')->default(30); // Estimated visit duration
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('route_id');
            $table->index('shop_id');
            $table->index('order');
            $table->unique(['route_id', 'shop_id']);
            $table->unique(['route_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_shop');
    }
};