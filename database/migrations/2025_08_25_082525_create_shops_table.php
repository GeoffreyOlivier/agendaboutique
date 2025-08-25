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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('France');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->enum('size', ['small', 'medium', 'large'])->nullable();
            $table->string('siret')->nullable();
            $table->string('vat_number')->nullable();
            $table->decimal('deposit_sale_rent', 8, 2)->nullable();
            $table->decimal('permanent_rent', 8, 2)->nullable();
            $table->decimal('deposit_sale_commission', 5, 2)->nullable();
            $table->decimal('permanent_commission', 5, 2)->nullable();
            $table->integer('monthly_permanences')->nullable();
            $table->string('website')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->text('opening_hours')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
