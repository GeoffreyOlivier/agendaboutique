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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('craftsman_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('base_price', 8, 2)->nullable();
            $table->decimal('min_price', 8, 2)->nullable();
            $table->decimal('max_price', 8, 2)->nullable();
            $table->boolean('price_hidden')->default(false);
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            $table->json('images')->nullable();
            $table->string('main_image')->nullable();
            $table->string('material')->nullable();
            $table->json('dimensions')->nullable();
            $table->text('care_instructions')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('available')->default(true);
            $table->integer('stock')->nullable();
            $table->string('reference')->nullable();
            $table->string('production_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
