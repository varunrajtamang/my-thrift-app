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
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained();
            $table->foreignId('condition_id')->constrained('product_conditions');
            $table->string('title', 255);
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->string('brand', 100)->nullable();
            $table->foreignId('size_id')->nullable()->constrained();
            $table->foreignId('color_id')->nullable()->constrained();
            $table->integer('quantity')->default(1);
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['active', 'sold', 'inactive', 'deleted'])->default('active');
            $table->integer('view_count')->default(0);
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
