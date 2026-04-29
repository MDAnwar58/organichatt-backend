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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('tran_id', 100);
            $table->string('total', 50)->nullable();
            $table->string('vat', 50)->nullable();
            $table->string('payable', 50)->nullable();
            $table->enum('order_status', ['pending', 'processing', 'on_the_way', 'delivered', 'cancelled'])->default('pending');
            $table->boolean('is_read')->default(0);
            $table->enum('payment_method', ['cash_on_delivery', 'bkash', 'nagad', 'rocket', 'card'])->default('cash_on_delivery');
            $table->foreign('user_id')->references('id')->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestamp('paid_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
