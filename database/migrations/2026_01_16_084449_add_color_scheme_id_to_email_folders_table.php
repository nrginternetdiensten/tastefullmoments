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
        Schema::table('email_folders', function (Blueprint $table) {
            $table->foreignId('color_scheme_id')->nullable()->after('description')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_folders', function (Blueprint $table) {
            $table->dropForeign(['color_scheme_id']);
            $table->dropColumn('color_scheme_id');
        });
    }
};
