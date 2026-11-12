<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DepartmentController;



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/noticias', [NewsController::class, 'index'])->name('noticias.index');
Route::get('/noticias/{news}', [NewsController::class, 'show'])->name('noticias.show');
Route::get('/eventos', [EventController::class, 'index'])->name('eventos.index');
Route::get('/eventos/{event}', [EventController::class, 'show'])->name('eventos.show');
Route::get('/departamentos', [DepartmentController::class, 'index'])->name('departamentos.index');