<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('location', 200)->nullable();
            $table->string('image_path')->nullable();
            $table->dateTime('starts_at'); // día y hora; el mes se saca de aquí
            $table->enum('status', ['draft', 'published', 'cancelled'])->default('published');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'starts_at'], 'idx_events_calendar');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
