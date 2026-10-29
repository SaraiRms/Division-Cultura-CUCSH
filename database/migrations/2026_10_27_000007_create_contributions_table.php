<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->enum('author_type', ['alumno', 'profesor']);
            $table->string('contact_email', 190);
            $table->text('comentarios')->nullable();
            $table->string('pdf_path');
            $table->string('title', 200)->nullable();       // lo completa el administrador
            $table->string('banner_path')->nullable();      // lo sube el administrador
            $table->enum('status', ['received', 'in_review', 'published', 'rejected'])->default('received');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['author_type', 'status', 'created_at'], 'idx_contributions_list');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
