<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversatorios', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('speaker', 200)->nullable();
            $table->text('description')->nullable();
            $table->dateTime('scheduled_at');
            $table->string('stream_url')->nullable();
            $table->enum('status', ['scheduled', 'live', 'finished'])->default('scheduled');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'scheduled_at'], 'idx_conv_list');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversatorios');
    }
};
