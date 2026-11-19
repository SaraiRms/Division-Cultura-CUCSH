<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('directory_admin', function (Blueprint $table) {
            // 1 = director ... 6 = personal académico (por defecto, el último nivel)
            $table->unsignedTinyInteger('jerarquia')->default(6)->after('cargo')->index();
        });
    }

    public function down(): void
    {
        Schema::table('directory_admin', function (Blueprint $table) {
            $table->dropIndex(['jerarquia']);
            $table->dropColumn('jerarquia');
        });
    }
};
