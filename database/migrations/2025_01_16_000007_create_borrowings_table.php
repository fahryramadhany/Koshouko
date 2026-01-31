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
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->dateTime('borrowed_at');
            $table->dateTime('due_date');
            $table->dateTime('returned_at')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned', 'overdue'])->default('pending');
            $table->integer('renewal_count')->default(0)->comment('Jumlah perpanjangan');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Index untuk query yang lebih cepat
            $table->index('user_id');
            $table->index('book_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
