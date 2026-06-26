<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procurements', function (Blueprint $table) {
            $table->dropForeign(['agency_id']);
            $table->dropColumn('agency_id');
        });

        Schema::table('procurement_items', function (Blueprint $table) {
            $table->foreignId('agency_id')->nullable()->after('procurement_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('procurement_items', function (Blueprint $table) {
            $table->dropForeign(['agency_id']);
            $table->dropColumn('agency_id');
        });

        Schema::table('procurements', function (Blueprint $table) {
            $table->foreignId('agency_id')->nullable()->after('procurement_number')->constrained()->nullOnDelete();
        });
    }
};