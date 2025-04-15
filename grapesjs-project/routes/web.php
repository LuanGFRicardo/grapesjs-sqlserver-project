<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrapesEditorController;

Route::get('/', [GrapesEditorController::class, 'menu'])->name('menu.templates');

Route::prefix('editor')->group(function () {
    Route::get('/', fn() => redirect()->route('menu.templates'));
    Route::get('/{template}', [GrapesEditorController::class, 'index']);
    Route::post('/{template}', [GrapesEditorController::class, 'index']);
});
