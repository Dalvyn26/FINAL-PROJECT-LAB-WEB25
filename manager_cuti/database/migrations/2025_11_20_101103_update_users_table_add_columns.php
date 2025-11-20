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
            $table->enum('role', ['admin', 'hrd', 'division_leader', 'user'])->default('user');
            $table->unsignedBigInteger('division_id')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('join_date')->nullable();
            $table->integer('leave_quota')->default(12);
            $table->boolean('active_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'division_id',
                'phone',
                'address',
                'join_date',
                'leave_quota',
                'active_status'
            ]);
        });
    }
};