<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('directory_admin', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 150);
            $table->string('cargo', 150)->nullable();
            $table->string('email', 190)->nullable();
            $table->string('phone', 40)->nullable();            // teléfono y extensión
            $table->string('cargo_detalle')->nullable();
            $table->string('nivel_academico', 100)->nullable();
            $table->string('grado_academico', 100)->nullable();
            $table->text('materias')->nullable();
            $table->string('area_conocimiento', 150)->nullable();
            $table->text('cv_summary')->nullable();
            $table->string('cv_path')->nullable();               // CV en PDF
            $table->string('photo_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabla puente: un maestro en varios departamentos y viceversa.
        Schema::create('department_directory_admin', function (Blueprint $table) {
            $table->unsignedInteger('department_id');
            $table->unsignedBigInteger('directory_admin_id');
            $table->primary(['department_id', 'directory_admin_id']);

            $table->foreign('department_id')->references('id')->on('departments')->cascadeOnDelete();
            $table->foreign('directory_admin_id')->references('id')->on('directory_admin')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_directory_admin');
        Schema::dropIfExists('directory_admin');
    }
};
