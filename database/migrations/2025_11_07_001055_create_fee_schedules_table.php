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
        Schema::create('fee_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('academic_year', 20);
            $table->enum('semester', ['1st', '2nd', 'Summer']);
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->decimal('late_penalty', 10, 2)->default(0);
            $table->boolean('allow_partial')->default(true);
            $table->string('target_program', 50)->nullable();
            $table->integer('target_year')->nullable();
            $table->foreignId('target_block_id')->nullable()->constrained('blocks')->onDelete('set null');
            $table->text('instructions')->nullable();
            $table->enum('status', ['draft', 'active', 'closed'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_schedules');
    }
};
