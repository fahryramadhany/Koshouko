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
            // make role_id nullable so creating users in a fresh DB (tests) doesn't require seeded roles
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('member_id')->unique()->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignIdFor('roles');
            $table->dropColumn(['role_id', 'phone', 'address', 'date_of_birth', 'member_id', 'status']);
            $table->dropSoftDeletes();
        });
    }
};
