<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DirectoryAdmin;
use App\Models\DirectoryRectoria;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{

    public function index(Request $request)
    {
        $vista = $request->query('vista') === 'rectoria' ? 'rectoria' : 'maestros';

        if ($vista === 'rectoria') {
            return view('directorio.index', [
                'vista' => $vista,
                'rectoria' => DirectoryRectoria::orderBy('id')->get(),
            ]);
        }

        $departamentoId = $request->integer('departamento');   // 0 si no viene

        // La División primero (show_card = 0) y luego los departamentos
        $departamentos = Department::orderBy('show_card')->orderBy('name')->get();

        $maestros = DirectoryAdmin::with('departments')
            ->when($departamentoId, fn ($q) => $q->whereHas(
                'departments',
                fn ($d) => $d->where('departments.id', $departamentoId)
            ))
            ->orderBy('full_name')
            ->get();

        return view('directorio.index', [
            'vista'              => $vista,
            'maestros'           => $maestros,
            'departamentos'      => $departamentos,
            'departamentoId'     => $departamentoId,
            'departamentoActual' => $departamentos->firstWhere('id', $departamentoId),
        ]);
    }

}   