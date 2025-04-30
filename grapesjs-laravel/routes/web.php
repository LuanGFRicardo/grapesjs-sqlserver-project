<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\GrapesEditorController;
use App\Http\Controllers\ComponenteController;

// Redirecionar para menu
Route::get('/', [MenuController::class, 'index'])->name('menu.templates');

// Prefixo 'editor'
Route::prefix('editor')->group(function () {
    // Redirecionar para menu
    Route::get('/', fn() => redirect()->route('menu.templates'));

    // Redirecionar para editor GrapesJS de template específico
    Route::get('/{template}', [GrapesEditorController::class, 'index']);

    // Enviar dados para editor GrapesJS de template específico
    Route::post('/{template}', [GrapesEditorController::class, 'index']);
});

// Redirecionar para gerenciador de componentes
Route::get('/componentes', [ComponenteController::class, 'index'])->name('componentes.gerenciar');

// Redirecionar para template exemplar
Route::get('/template', [GrapesEditorController::class, 'template']);