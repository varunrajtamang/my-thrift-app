<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            // Add daily to enum: requires doctrine/dbal to work
            $table->enum('duration_type', ['daily', 'weekly', 'monthly', 'yearly'])->change();

            // Allow nullable listings for unlimited plans
            $table->integer('max_listings')->nullable()->change();

            // Add default to duration value
            $table->integer('duration_value')->default(1)->change();

            // Add description default (optional)
            $table->text('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            // Revert enum
            $table->enum('duration_type', ['weekly', 'monthly', 'yearly'])->change();

            $table->integer('max_listings')->nullable(false)->change();
            $table->integer('duration_value')->default(null)->change();
        });
    }
};

