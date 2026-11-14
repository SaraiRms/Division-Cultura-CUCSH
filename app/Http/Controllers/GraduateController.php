<?php

namespace App\Http\Controllers;

use App\Models\Department;

class GraduateController extends Controller
{
    public function index()
    {
        $departamentos = Department::cards()
            ->with(['programs' => function ($q) {
                $q->whereHas('graduates', fn ($g) => $g->where('status', 'published'))
                  ->with(['graduates' => fn ($g) => $g->where('status', 'published')->latest()]);
            }])
            ->orderBy('name')
            ->get()
            ->filter(fn ($d) => $d->programs->isNotEmpty())
            ->values();

        return view('egresados.index', compact('departamentos'));
    }
}