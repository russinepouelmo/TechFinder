<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\competenceController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\UserCompetenceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Competence routes
Route::apiResource('competences', competenceController::class);
Route::get('competences/search/{keyword}', [competenceController::class, 'search']);

// Utilisateur routes
Route::apiResource('utilisateurs', UtilisateurController::class);

// Intervention routes
Route::apiResource('interventions', InterventionController::class);

// User Competence (Inventaire) routes
Route::apiResource('user-competences', UserCompetenceController::class)->only('index', 'store', 'destroy');
Route::get('user-competences/user/{code_user}', [UserCompetenceController::class, 'showByUser']);
