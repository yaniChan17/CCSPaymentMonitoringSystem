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
        // Add extended fields to students table
        Schema::table('students', function (Blueprint $table) {
            $table->string('guardian_name')->nullable()->after('contact_number');
            $table->string('guardian_contact')->nullable()->after('guardian_name');
            $table->text('address')->nullable()->after('guardian_contact');
            $table->string('photo')->nullable()->after('address');
        });

        // Add photo field to users table for admins and treasurers
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('role');
            $table->string('contact_number')->nullable()->after('photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['guardian_name', 'guardian_contact', 'address', 'photo']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['photo', 'contact_number']);
        });
    }
};
