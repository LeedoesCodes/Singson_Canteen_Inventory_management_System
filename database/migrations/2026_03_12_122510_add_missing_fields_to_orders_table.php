<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add user_id foreign key
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null')
                  ->after('id');
            
            // Add order_number for tracking
            $table->string('order_number')->unique()->after('user_id');
            
            // Modify status enum to include all required statuses
            $table->enum('status', [
                'pending',
                'preparing',
                'ready',
                'completed',
                'cancelled'
            ])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'order_number']);
            
            // Revert status back to original
            $table->enum('status', [
                'pending',
                'preparing',
                'completed'
            ])->default('pending')->change();
        });
    }
};