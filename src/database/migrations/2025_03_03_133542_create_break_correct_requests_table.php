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
        Schema::create('break_correct_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_correct_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('break_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('break_start')->nullable();
            $table->timestamp('break_end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('break_correct_requests');
    }
};
