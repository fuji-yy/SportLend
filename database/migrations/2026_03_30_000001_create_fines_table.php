<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_id')->unique()->constrained('borrowings')->onDelete('cascade');
            $table->unsignedInteger('days_late')->default(0);
            $table->unsignedBigInteger('amount_late')->default(0);
            $table->unsignedBigInteger('amount_damage')->default(0);
            $table->unsignedBigInteger('amount_total')->default(0);
            $table->enum('status', ['unpaid', 'paid', 'waived'])->default('unpaid');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
