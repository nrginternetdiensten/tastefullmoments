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
        Schema::create('order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->decimal('price_tax', 10, 2);
            $table->decimal('price_exc', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('total_exc', 10, 2);
            $table->decimal('total_tax', 10, 2);
            $table->foreignId('tax_id')->nullable()->constrained('invoice_taxes')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_lines');
    }
};
