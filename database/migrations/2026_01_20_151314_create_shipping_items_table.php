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
        Schema::create('shipping_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->nullable()->constrained()->cascadeOnDelete();

            // Delivery fields
            $table->date('delivery_date')->nullable();
            $table->time('delivery_time')->nullable();
            $table->string('delivery_first_name')->nullable();
            $table->string('delivery_last_name')->nullable();
            $table->string('delivery_street')->nullable();
            $table->string('delivery_housenumber')->nullable();
            $table->string('delivery_zipcode')->nullable();
            $table->string('delivery_city')->nullable();
            $table->unsignedBigInteger('delivery_country_id')->nullable();

            // Pickup fields
            $table->unsignedBigInteger('pickup_option_id')->nullable();
            $table->date('pickup_date')->nullable();
            $table->time('pickup_time')->nullable();
            $table->string('pickup_first_name')->nullable();
            $table->string('pickup_last_name')->nullable();
            $table->string('pickup_street')->nullable();
            $table->string('pickup_housenumber')->nullable();
            $table->string('pickup_zipcode')->nullable();
            $table->string('pickup_city')->nullable();
            $table->unsignedBigInteger('pickup_country_id')->nullable();

            // Return fields
            $table->unsignedBigInteger('return_option_id')->nullable();
            $table->date('return_date')->nullable();
            $table->time('return_time')->nullable();
            $table->string('return_first_name')->nullable();
            $table->string('return_last_name')->nullable();
            $table->string('return_street')->nullable();
            $table->string('return_housenumber')->nullable();
            $table->string('return_zipcode')->nullable();
            $table->string('return_city')->nullable();
            $table->unsignedBigInteger('return_country_id')->nullable();

            // Pricing
            $table->decimal('price_delivery', 10, 2)->nullable();
            $table->decimal('price_pickup', 10, 2)->nullable();
            $table->decimal('price_return', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();

            // Status and transaction
            $table->unsignedBigInteger('status_id')->nullable();
            $table->boolean('transaction_done')->default(false);
            $table->string('transaction_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_items');
    }
};
