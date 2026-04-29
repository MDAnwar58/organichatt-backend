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
        Schema::create('invoice_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');

            $table->string('qty', 50);
            $table->string('sale_price', 50)->nullable();
            $table->string('sale_discount_price', 50)->nullable();
            $table->enum('product_optional_type', ['weight', 'size', 'size_number'])->nullable();
            $table->string('product_optional', 50)->nullable();
            $table->unsignedBigInteger('weight_id')->nullable();
            $table->string('weight_price', 50)->nullable();
            $table->string('weight_discount_price', 50)->nullable();
            $table->unsignedBigInteger('size_id')->nullable();
            $table->string('size_price', 50)->nullable();
            $table->string('size_discount_price', 50)->nullable();
            $table->unsignedBigInteger('size_number_id')->nullable();
            $table->string('size_number_price', 50)->nullable();
            $table->string('size_number_discount_price', 50)->nullable();

            $table->foreign('invoice_id')->references('id')->on('invoices')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('product_id')->references('id')->on('products')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('user_id')->references('id')->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('weight_id')->references('id')->on('weights')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('size_id')->references('id')->on('sizes')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('size_number_id')->references('id')->on('size_numbers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_products');
    }
};
