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
        Schema::create('settings_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->text('value')->nullable();
            $table->foreignId('fieldtype_id')->nullable()->constrained('settings_field_types')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('settings_categories')->nullOnDelete();
            $table->integer('list_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings_items');
    }
};
