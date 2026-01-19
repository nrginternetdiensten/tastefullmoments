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
        Schema::table('ticket_statuses', function (Blueprint $table) {
            $table->foreignId('color_scheme_id')->nullable()->after('name')->constrained()->nullOnDelete();
        });

        Schema::table('ticket_channels', function (Blueprint $table) {
            $table->foreignId('color_scheme_id')->nullable()->after('name')->constrained()->nullOnDelete();
        });

        Schema::table('ticket_types', function (Blueprint $table) {
            $table->foreignId('color_scheme_id')->nullable()->after('name')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_statuses', function (Blueprint $table) {
            $table->dropForeign(['color_scheme_id']);
            $table->dropColumn('color_scheme_id');
        });

        Schema::table('ticket_channels', function (Blueprint $table) {
            $table->dropForeign(['color_scheme_id']);
            $table->dropColumn('color_scheme_id');
        });

        Schema::table('ticket_types', function (Blueprint $table) {
            $table->dropForeign(['color_scheme_id']);
            $table->dropColumn('color_scheme_id');
        });
    }
};
