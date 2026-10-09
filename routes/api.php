<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrdenanzaController;
use App\Http\Controllers\Api\AcuerdoConcejoController;
use App\Http\Controllers\Api\ResolucionAlcaldiaController; 
use App\Http\Controllers\Api\ResolucionGerenciaController;
use App\Http\Controllers\Api\GestionController;
use App\Http\Controllers\Api\InstrumentoGestionController;
use App\Http\Controllers\Api\DocInteresController;
use App\Http\Controllers\Api\DecretoController;
use App\Http\Controllers\Api\ProcesoSeleccionController;
use App\Http\Controllers\Api\ConvocatoriaCASController;
use App\Http\Controllers\Api\DirectivaController; // <-- 1. IMPORTAR ARRIBA


// GRUPO PRIVADO / PROTEGIDO (Solo para el administrador logueado)
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});

Route::middleware('throttle:60,1')->group(function () {
    
    // API de Ordenanzas Municipales
    Route::get('/ordenanzas', [OrdenanzaController::class, 'index']);

    // API de Acuerdos de Concejo
    Route::get('/acuerdos-concejo', [AcuerdoConcejoController::class, 'index']);
    
    // 2. CORRECCIÓN: Movida aquí para que sea pública y use el nombre correcto del controlador
    Route::get('/resoluciones-alcaldia', [ResolucionAlcaldiaController::class, 'index']);
    Route::get('/convocatorias-cas', [ConvocatoriaCASController::class, 'index']); 
    Route::get('/resoluciones-gerencia', [ResolucionGerenciaController::class, 'index']); 
    Route::get('/directivas', [DirectivaController::class, 'index']); // <-- 2. AGREGAR AQUÍ
    Route::get('/decretos', [DecretoController::class, 'index']);
    Route::get('/decretos/{id}', [DecretoController::class, 'show']);

    Route::get('/gestion', [GestionController::class, 'show']);

    Route::get('/instrumentos-gestion', [InstrumentoGestionController::class, 'index']);
    Route::get('/instrumentos-gestion/{id}', [InstrumentoGestionController::class, 'show']);
    Route::get('/documentos-interes', [DocInteresController::class, 'index']);
    Route::get('/documentos-interes/{id}', [DocInteresController::class, 'show']);
    Route::get('/procesos-seleccion', [ProcesoSeleccionController::class, 'index']);


    
});
