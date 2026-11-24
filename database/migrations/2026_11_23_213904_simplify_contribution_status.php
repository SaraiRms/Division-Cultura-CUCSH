<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Lo que estaba "recibido" pasa a "en revisión"
        DB::table('contributions')->where('status', 'received')->update(['status' => 'in_review']);

        Schema::table('contributions', function (Blueprint $table) {
            $table->enum('status', ['in_review', 'published', 'rejected'])->default('in_review')->change();
        });
    }

    public function down(): void
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->enum('status', ['received', 'in_review', 'published', 'rejected'])->default('received')->change();
        });
    }
};
