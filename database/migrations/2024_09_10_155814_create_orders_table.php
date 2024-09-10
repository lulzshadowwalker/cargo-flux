<?php

use App\Enums\OrderPaymentMethod;
use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2);
            $table->enum('status', array_map(fn($status) => $status->value, OrderStatus::cases()));
            $table->enum('payment_method', array_map(fn($method) => $method->value, OrderPaymentMethod::cases()));
            $table->enum('payment_status', array_map(fn($status) => $status->value, OrderPaymentStatus::cases()));
            $table->dateTime('scheduled_at')->nullable();
            $table->decimal('pickup_location_latitude', 10, 7);
            $table->decimal('pickup_location_longitude', 10, 7);
            $table->decimal('delivery_location_latitude', 10, 7);
            $table->decimal('delivery_location_longitude', 10, 7);
            $table->decimal('current_location_latitude', 10, 7)->nullable();
            $table->decimal('current_location_longitude', 10, 7)->nullable();
            $table->dateTime('current_location_recorded_at')->nullable();
            $table->foreignId('customer_id');
            $table->foreignId('driver_id');
            $table->foreignId('currency_id');
            $table->foreignId('truck_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
