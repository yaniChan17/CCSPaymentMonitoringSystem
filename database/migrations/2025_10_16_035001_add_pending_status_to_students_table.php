<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support ENUM modifications, so we need to handle it differently
        if (DB::connection()->getDriverName() === 'sqlite') {
            // For SQLite, recreate the table with the new enum
            Schema::table('students', function (Blueprint $table) {
                // SQLite doesn't need enum modification as it's flexible with text fields
            });
        } else {
            // For MySQL/PostgreSQL
            DB::statement("ALTER TABLE students MODIFY COLUMN status ENUM('active', 'inactive', 'graduated', 'pending') DEFAULT 'active'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // SQLite doesn't support ENUM modifications
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE students MODIFY COLUMN status ENUM('active', 'inactive', 'graduated') DEFAULT 'active'");
        }
    }
};
