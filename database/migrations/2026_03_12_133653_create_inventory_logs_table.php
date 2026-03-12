<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('previous_stock');
            $table->integer('new_stock');
            $table->integer('quantity_change');
            $table->enum('type', ['restock', 'order', 'adjustment', 'cancellation']);
            $table->string('reference_type')->nullable(); // 'order', 'stock_entry', etc.
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of the related record
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            // Index for faster queries
            $table->index(['product_id', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};