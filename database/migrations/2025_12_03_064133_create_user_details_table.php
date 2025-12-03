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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->json('professional_category_id')->nullable();
            $table->enum('education_type', ['ssc','hsc','diploma','bachelor','masters','other'])->nullable();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->foreignId('district_id')->constrained('districts')->cascadeOnDelete();
            $table->string('area');
            $table->foreignId('thana_id')->constrained('thanas')->cascadeOnDelete();
            $table->string('permanent_address')->nullable();
            $table->text('nid_front_side')->nullable();
            $table->text('nid_back_side')->nullable();
            $table->text('certificates')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
