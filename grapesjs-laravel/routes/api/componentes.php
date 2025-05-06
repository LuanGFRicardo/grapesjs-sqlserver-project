<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponenteController;

Route::prefix('componentes')->group(function () {
    Route::get('/{id}', [ComponenteController::class, 'buscarComponente']);
    Route::post('/', [ComponenteController::class, 'salvarComponente']);
    Route::put('/{id}', [ComponenteController::class, 'atualizarComponente']);
    Route::delete('/{id}', [ComponenteController::class, 'excluirComponente']);
    Route::get('/', [ComponenteController::class, 'listarComponentes']);
});