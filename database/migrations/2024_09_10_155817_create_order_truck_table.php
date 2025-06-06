<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_truck', function (Blueprint $table) {
            $table->foreignId('order_id');
            $table->foreignId('truck_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_truck');
    }
};
