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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('leave_type', ['annual', 'sick']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            $table->text('reason');
            $table->string('attachment_path')->nullable();
            $table->enum('status', ['pending', 'approved_by_leader', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_note')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};