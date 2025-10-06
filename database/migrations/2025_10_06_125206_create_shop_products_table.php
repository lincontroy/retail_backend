<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->text('notes')->nullable();
            $table->json('images')->nullable(); // Store multiple image paths
            $table->timestamps();

            // Ensure unique product per shop
            $table->unique(['shop_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('shop_products');
    }
};