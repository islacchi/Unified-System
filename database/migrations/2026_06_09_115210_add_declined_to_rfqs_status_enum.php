<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE rfqs MODIFY COLUMN status ENUM(
            'Received', 'Reviewing', 'Quoted', 'Awarded', 'Lost', 'Declined'
        ) NOT NULL DEFAULT 'Received'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE rfqs MODIFY COLUMN status ENUM(
            'Received', 'Reviewing', 'Quoted', 'Awarded', 'Lost'
        ) NOT NULL DEFAULT 'Received'");
    }
};