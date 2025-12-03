<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('card_type', 255)->nullable();
            $table->string('transaction_id', 255);
            $table->string('transaction_date', 255)->nullable();
            $table->string('currency', 50)->nullable();
            $table->string('ssl_status', 50)->nullable();
            $table->string('amount_type', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
