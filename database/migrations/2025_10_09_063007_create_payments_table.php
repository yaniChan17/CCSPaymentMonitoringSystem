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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('restrict'); // Treasurer who recorded it
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->enum('status', ['paid', 'pending', 'overdue'])->default('paid');
            $table->enum('payment_method', ['cash', 'gcash', 'maya', 'paypal'])->default('cash');
            $table->string('reference_number')->nullable(); // For online/bank payments
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
