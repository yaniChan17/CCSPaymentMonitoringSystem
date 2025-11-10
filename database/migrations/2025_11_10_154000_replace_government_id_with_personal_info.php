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
        Schema::table('users', function (Blueprint $table) {
            // Remove government ID fields
            $table->dropColumn(['government_id_type', 'government_id_number', 'government_id_file']);
            
            // Add personal information fields for admin/treasurer
            $table->string('course')->nullable()->after('student_id');
            $table->string('year_level')->nullable()->after('course');
            $table->string('block')->nullable()->after('year_level');
            $table->string('father_name')->nullable()->after('contact_number');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->text('address')->nullable()->after('mother_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove personal info fields
            $table->dropColumn(['course', 'year_level', 'block', 'father_name', 'mother_name', 'address']);
            
            // Restore government ID fields
            $table->string('government_id_type')->nullable()->after('remember_token');
            $table->string('government_id_number')->nullable()->after('government_id_type');
            $table->string('government_id_file')->nullable()->after('government_id_number');
        });
    }
};
