<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\ConversatorioController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\ContributionController;



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/noticias', [NewsController::class, 'index'])->name('noticias.index');
Route::get('/noticias/{news}', [NewsController::class, 'show'])->name('noticias.show');
Route::get('/eventos', [EventController::class, 'index'])->name('eventos.index');
Route::get('/eventos/{event}', [EventController::class, 'show'])->name('eventos.show');
Route::get('/departamentos', [DepartmentController::class, 'index'])->name('departamentos.index');
Route::get('/directorio', [DirectoryController::class, 'index'])->name('directorio.index');
Route::get('/podcast', [PodcastController::class, 'index'])->name('podcast.index');
Route::get('/conversatorios', [ConversatorioController::class, 'index'])->name('conversatorios.index');
//
Route::redirect('/integracion', '/integracion/alumnos');
Route::prefix('integracion')->name('integracion.')->group(function () {
Route::get('documento/{contribution}', [ContributionController::class, 'document'])->name('documento');

Route::get('{tipo}', [ContributionController::class, 'index'])
    ->whereIn('tipo', ['alumnos', 'profesores'])->name('index');

Route::get('{tipo}/participar', [ContributionController::class, 'create'])
    ->whereIn('tipo', ['alumnos', 'profesores'])->name('create');

Route::post('{tipo}/participar', [ContributionController::class, 'store'])
    ->whereIn('tipo', ['alumnos', 'profesores'])
    ->middleware('throttle:aportaciones')
    ->name('store');

});

Route::get('/archivos-privados/aportaciones/{contribution}', [ContributionController::class, 'adminDocument'])
    ->name('aportaciones.pdf');