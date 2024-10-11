<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('route_group_destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_group_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('delivery_state_id')->references('id')->on('states')->constrained();
            $table->unique(['route_group_id', 'delivery_state_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_group_destinations');
    }
};
