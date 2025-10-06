<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('route_shop', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique('route_shop_route_id_order_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('route_shop', function (Blueprint $table) {
            // Recreate the unique constraint if needed
            $table->unique(['route_id', 'order'], 'route_shop_route_id_order_unique');
        });
    }
};