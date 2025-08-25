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
        Schema::create('craftsmen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->text('description')->nullable();
            $table->string('specialty')->nullable();
            $table->integer('experience_years')->nullable();
            $table->string('education')->nullable();
            $table->json('certifications')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('France');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->decimal('daily_rate', 8, 2)->nullable();
            $table->enum('availability', ['available', 'busy', 'unavailable'])->default('available');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('active')->default(true);
            $table->string('avatar')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('craftsmen');
    }
};
