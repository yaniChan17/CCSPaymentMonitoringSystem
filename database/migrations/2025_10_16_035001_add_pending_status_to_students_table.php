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
        // SQLite doesn't support ENUM modifications and uses flexible text fields,
        // so no schema changes are needed. MySQL/PostgreSQL require explicit ENUM updates.
        if (DB::connection()->getDriverName() !== 'sqlite') {
            // For MySQL/PostgreSQL - modify ENUM to include 'pending' status
            DB::statement("ALTER TABLE students MODIFY COLUMN status ENUM('active', 'inactive', 'graduated', 'pending') DEFAULT 'active'");
        }
        // SQLite: No action needed - text fields already accept 'pending' value
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
