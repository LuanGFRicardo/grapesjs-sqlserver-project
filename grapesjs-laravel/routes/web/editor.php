<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrapesEditorController;

Route::prefix('editor')->group(function () {
    Route::get('/{template}', [GrapesEditorController::class, 'index'])->name('editor.template');
    Route::post('/{template}', [GrapesEditorController::class, 'index']);
});