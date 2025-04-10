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
        Schema::create('transactions', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained('seller_subscriptions')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('transaction_type', ['purchase', 'sale', 'subscription', 'refund']);
            $table->string('payment_method', 50);
            $table->enum('transaction_status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
