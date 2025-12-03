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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('header_title', 255)->nullable();
            $table->string('title', 191)->nullable();
            $table->longText('description')->nullable();
            $table->longText('keywords')->nullable();
            $table->string('address', 191)->nullable();
            $table->string('phone', 191)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('another_email', 255)->nullable();
            $table->string('logo', 191)->nullable();
            $table->string('favicon', 255)->nullable();
            $table->string('meta_image', 191)->nullable();
            $table->string('facebook_link', 191)->nullable();
            $table->string('instagram_link', 191)->nullable();
            $table->string('twitter_link', 191)->nullable();
            $table->string('linkedin_link', 191)->nullable();
            $table->string('youtube_link', 191)->nullable();
            $table->text('google_map_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
