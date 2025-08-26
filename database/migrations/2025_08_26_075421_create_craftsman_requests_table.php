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
        Schema::create('craftsman_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('craftsman_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->json('specifications')->nullable();
            $table->integer('requested_quantity')->nullable();
            $table->decimal('estimated_budget', 10, 2)->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'in_progress', 'completed'])->default('pending');
            $table->text('craftsman_response')->nullable();
            $table->decimal('proposed_price', 10, 2)->nullable();
            $table->date('response_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('craftsman_requests');
    }
};
