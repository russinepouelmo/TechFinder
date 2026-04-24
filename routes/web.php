<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\CompetenceController;
use App\Http\Controllers\Web\UtilisateurController;

Route::get('/', function () {
    return view('welcome');
});
Route::name('web.')->group(function () {
    Route::get('/Web/competences', [CompetenceController::class, 'index'])->name('competences.index');
    Route::post('/Web/competences', [CompetenceController::class, 'store'])->name('competences.store');
    Route::put('/Web/competences/{code_comp}', [CompetenceController::class, 'update'])->name('competences.update');
    Route::delete('/Web/competences/{code_comp}', [CompetenceController::class, 'destroy'])->name('competences.destroy');

    Route::get('/Web/utilisateurs', [UtilisateurController::class, 'index'])->name('utilisateurs.index');
    Route::post('/Web/utilisateurs', [UtilisateurController::class, 'store'])->name('utilisateurs.store');
    Route::put('/Web/utilisateurs/{code_user}', [UtilisateurController::class, 'update'])->name('utilisateurs.update');
    Route::delete('/Web/utilisateurs/{code_user}', [UtilisateurController::class, 'destroy'])->name('utilisateurs.destroy');
});
