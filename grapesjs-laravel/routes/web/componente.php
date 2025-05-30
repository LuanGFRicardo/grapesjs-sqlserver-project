<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponenteController;

// Rotas de componentes
Route::prefix('componentes')->group(function () {
    Route::get('/', [ComponenteController::class, 'listarComponentes']); // Lista componentes
});
