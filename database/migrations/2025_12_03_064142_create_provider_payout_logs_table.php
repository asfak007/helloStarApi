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
        Schema::create('provider_payout_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->json('earning_ids');
            $table->foreignId('payout_request_id')->constrained('provider_payout_requests')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['auto','manual']);
            $table->foreignId('payout_account_id')->constrained('provider_payout_accounts')->cascadeOnDelete();
            $table->enum('payout_status', ['processing','success','failed']);
            $table->string('transaction_id')->nullable();
            $table->timestamp('payout_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_payout_logs');
    }
};
