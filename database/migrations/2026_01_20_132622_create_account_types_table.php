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
        Schema::create('account_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price_month', 10, 2)->default(0);
            $table->decimal('price_quarter', 10, 2)->default(0);
            $table->decimal('price_year', 10, 2)->default(0);
            $table->foreignId('tax_id')->nullable()->constrained('invoice_taxes')->nullOnDelete();
            $table->integer('list_order')->default(0);
            $table->boolean('active')->default(true);
            $table->foreignId('color_scheme_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_types');
    }
};
