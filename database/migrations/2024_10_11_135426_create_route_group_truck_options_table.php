<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('route_group_truck_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_group_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('truck_category_id')->constrained();
            $table->decimal('amount', 15, 2);
            $table->foreignId('currency_id')->constrained();
            $table->unique(['route_group_id', 'truck_category_id'], 'route_group_truck_options_unique');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_group_truck_options');
    }
};
