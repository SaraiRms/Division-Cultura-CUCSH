<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    //se agrega el campo phone_ext a la tabla directory_admin para almacenar la extensión del teléfono del administrador de directorio.

   public function up(): void
   {
       Schema::table('directory_admin', function (Blueprint $table) {
           $table->string('phone_ext', 10)->nullable()->after('phone');
       });
   }

    /**
     * Reverse the migrations.
     */

    //se elimina el campo phone_ext de la tabla directory_admin en caso de que se desee revertir la migración.

   public function down(): void
   {
       Schema::table('directory_admin', function (Blueprint $table) {
           $table->dropColumn('phone_ext');
       });
   }
};
