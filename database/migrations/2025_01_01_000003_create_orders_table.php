<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->string('customer_name', 100);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', [
                'pending_payment',
                'pending_verification',
                'processing',
                'ready',
                'completed',
                'cancelled'
            ])->default('pending_payment');
            $table->timestamp('payment_deadline')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('order_number');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
