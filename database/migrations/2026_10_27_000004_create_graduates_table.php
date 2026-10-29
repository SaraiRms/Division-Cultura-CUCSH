<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('graduates', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('program_id');
            $table->string('title', 200)->nullable();
            $table->string('image_path');
            $table->enum('status', ['pending', 'published', 'rejected'])->default('pending');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('program_id')->references('id')->on('academic_programs')->cascadeOnDelete();
            $table->index(['program_id', 'status'], 'idx_graduates_program');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('graduates');
    }
};
