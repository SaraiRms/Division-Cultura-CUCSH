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
       Schema::table('directory_admin', function (Blueprint $table) {
           $table->dropColumn(['cargo_detalle', 'grado_academico']);
       });
   }

    /**
     * Reverse the migrations.
     */
   public function down(): void
   {
       Schema::table('directory_admin', function (Blueprint $table) {
           $table->string('cargo_detalle')->nullable()->after('cargo');
           $table->string('grado_academico', 100)->nullable()->after('nivel_academico');
       });
   }
};
