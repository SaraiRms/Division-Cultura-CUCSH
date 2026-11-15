<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        foreach (['news', 'graduates'] as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                $table->text('review_note')->nullable();
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        foreach (['news', 'graduates'] as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                $table->dropForeign(['reviewed_by']);
                $table->dropColumn(['review_note', 'reviewed_by']);
            });
        }
    }

};
