<?php

use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->morphs('payable');
            $table->string('external_reference')->unique();
            $table->enum('status', array_map(fn($status) => $status->value, PaymentStatus::cases()));
            $table->enum('gateway', array_map(fn($status) => $status->value, PaymentGateway::cases()));
            $table->json('details')->nullable();
            $table->decimal('amount', 15, 2);
            $table->foreignId('currency_id');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
