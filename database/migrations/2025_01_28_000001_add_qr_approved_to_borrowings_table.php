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
        Schema::table('borrowings', function (Blueprint $table) {
            $table->string('qr_code')->nullable()->comment('QR code path untuk bukti approval');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->comment('User yang approve (admin/pustakawan)');
            $table->timestamp('approved_at')->nullable()->comment('Tanggal approval');
            $table->text('rejection_reason')->nullable()->comment('Alasan penolakan jika rejected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['qr_code', 'approved_by', 'approved_at', 'rejection_reason']);
        });
    }
};
