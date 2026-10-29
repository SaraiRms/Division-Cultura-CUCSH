<?php

namespace Database\Seeders;

use App\Models\AcademicProgram;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentsSeeder extends Seeder
{
    public function run(): void
    {
        // La División se guarda con show_card = false: sirve como adscripción en el directorio, sin tarjeta.
        
        $departments = [
            'Departamento de Estudios de la Comunicación Social' => true,
            'Departamento de Estudios en Lenguas Indígenas' => true,
            'Departamento de Estudios Literarios' => true,
            'Departamento de Estudios Mesoamericanos y Mexicanos' => true,
            'División de Estudios de la Cultura' => false,
        ];

        foreach ($departments as $name => $showCard) {
            Department::updateOrCreate(['name' => $name], ['show_card' => $showCard]);
        }

        $programs = [
            'Departamento de Estudios de la Comunicación Social' => [
                ['Licenciatura en Comunicación Pública', 'licenciatura', 1],
                ['Maestría en Comunicación', 'maestria', 2],
            ],
            'Departamento de Estudios en Lenguas Indígenas' => [
                ['Maestría en Lingüística Aplicada', 'maestria', 1],
            ],
            'Departamento de Estudios Literarios' => [
                ['Licenciatura en Escritura Creativa', 'licenciatura', 1],
                ['Maestría en Literaturas Interamericanas', 'maestria', 2],
                ['Doctorado en Humanidades', 'doctorado', 3],
                ['Centro de Estudios de Literatura Latinoamericana Julio Cortázar', 'centro', 4],
            ],
            'Departamento de Estudios Mesoamericanos y Mexicanos' => [
                ['Maestría en Estudios Mesoamericanos', 'maestria', 1],
            ],
        ];

        foreach ($programs as $departmentName => $items) {
            $department = Department::where('name', $departmentName)->firstOrFail();

            foreach ($items as [$name, $type, $order]) {
                AcademicProgram::updateOrCreate(
                    ['department_id' => $department->id, 'name' => $name],
                    ['type' => $type, 'sort_order' => $order],
                );
            }
        }
    }
}
