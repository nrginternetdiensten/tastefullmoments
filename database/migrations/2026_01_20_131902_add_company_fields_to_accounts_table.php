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
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('name');
            $table->string('street_name')->nullable()->after('company_name');
            $table->string('house_number')->nullable()->after('street_name');
            $table->string('zipcode')->nullable()->after('house_number');
            $table->string('city')->nullable()->after('zipcode');
            $table->string('email_address')->nullable()->after('city');
            $table->string('telephone_number')->nullable()->after('email_address');
            $table->string('kvk')->nullable()->after('telephone_number');
            $table->string('btw')->nullable()->after('kvk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'street_name',
                'house_number',
                'zipcode',
                'city',
                'email_address',
                'telephone_number',
                'kvk',
                'btw',
            ]);
        });
    }
};
