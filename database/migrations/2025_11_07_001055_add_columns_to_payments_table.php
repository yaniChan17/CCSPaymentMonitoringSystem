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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('fee_schedule_id')->nullable()->after('student_id')->constrained('fee_schedules')->onDelete('set null');
            $table->timestamp('recorded_at')->useCurrent()->after('status');
            $table->boolean('is_late')->default(false)->after('recorded_at');
            $table->foreignId('edited_by')->nullable()->after('recorded_by')->constrained('users')->onDelete('set null');
            $table->timestamp('edited_at')->nullable()->after('edited_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['fee_schedule_id']);
            $table->dropForeign(['edited_by']);
            $table->dropColumn(['fee_schedule_id', 'recorded_at', 'is_late', 'edited_by', 'edited_at']);
        });
    }
};
