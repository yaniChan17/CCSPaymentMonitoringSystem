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
        // Change the enum to include 'pending' status
        DB::statement("ALTER TABLE students MODIFY COLUMN status ENUM('active', 'inactive', 'graduated', 'pending') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE students MODIFY COLUMN status ENUM('active', 'inactive', 'graduated') DEFAULT 'active'");
    }
};
