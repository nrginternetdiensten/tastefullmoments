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
        Schema::create('lead_items', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('companyname')->nullable();
            $table->string('streetname')->nullable();
            $table->string('housenumber')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('city')->nullable();
            $table->string('emailadres')->nullable();
            $table->string('phonenumber')->nullable();
            $table->string('ipaddress')->nullable();
            $table->text('internal_note')->nullable();
            $table->foreignId('lead_status_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lead_channel_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lead_category_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_items');
    }
};
