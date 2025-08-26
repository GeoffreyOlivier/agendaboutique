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
        Schema::create('shop_craftsman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('craftsman_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'active', 'inactive', 'rejected'])->default('pending');
            $table->text('shop_comment')->nullable();
            $table->text('craftsman_comment')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('commission_percentage', 5, 2)->default(0);
            $table->boolean('permanent_exhibition')->default(false);
            $table->boolean('temporary_exhibition')->default(false);
            $table->timestamps();
            
            $table->unique(['shop_id', 'craftsman_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_craftsman');
    }
};
