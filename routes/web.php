<?php

use App\Http\Controllers\CandidatosController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VacanteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [VacanteController::class, 'index'])->middleware(['auth', 'verified', 'rol.reclutador'])->name('vacantes.index');
Route::get('/vacantes/create', [VacanteController::class, 'create'])->middleware(['auth', 'verified'])->name('vacantes.create');
Route::get('/vacantes/{vacante}/edit', [VacanteController::class, 'edit'])->middleware(['auth', 'verified'])->name('vacantes.edit');
Route::get('/vacantes/{vacante}', [VacanteController::class, 'show'])->name('vacantes.show');
Route::get('/candidatos/{vacante}', [CandidatosController::class, 'index'])->name('candidatos.index');
Route::get('/candidatos/cv/{candidato}', [CandidatosController::class, 'show'])
    ->middleware(['auth', 'verified', 'rol.reclutador'])
    ->name('candidatos.cv');
// Notificaciones
Route::get('/notificaciones', NotificacionController::class)->middleware(['auth', 'verified','rol.reclutador'])->name('notificaciones');
require __DIR__.'/auth.php';
