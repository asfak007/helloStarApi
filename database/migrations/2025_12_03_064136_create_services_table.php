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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->text('conditions')->nullable();
            $table->integer('amount');
            $table->integer('platform_fee');
            $table->boolean('partial_payment')->default(false);
            $table->decimal('partial_payment_percentage', 5, 2)->nullable();
            $table->decimal('provider_percentage', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
