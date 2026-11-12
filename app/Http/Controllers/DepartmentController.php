<?php

namespace App\Http\Controllers;

use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('departamentos.index', [
            // La División (sin tarjeta): su descripción va en la Presentación
            'division' => Department::where('show_card', false)->first(),

            // Los 4 departamentos con sus programas ya cargados
            'departamentos' => Department::cards()->with('programs')->orderBy('name')->get(),
        ]);
    }
}