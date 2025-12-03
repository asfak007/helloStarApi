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
        Schema::create('service_requirement_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_requirement_id')->constrained('service_requirements')->cascadeOnDelete();
            $table->text('requirement_icon')->nullable();
            $table->string('requirement_title', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requirement_options');
    }
};
