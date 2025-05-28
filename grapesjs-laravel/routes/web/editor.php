<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrapesEditorController;

Route::prefix('editor')->group(function () {
    Route::get('/{template}', [GrapesEditorController::class, 'index'])->name('editor.template');
    Route::post('/salvar-template', [GrapesEditorController::class, 'salvarTemplate']);
    Route::get('/get-template/{title}', [GrapesEditorController::class, 'carregarUltimaVersao']);
    Route::post('/upload-imagem', [GrapesEditorController::class, 'uploadImagem']);
    Route::post('/baixar-template', [GrapesEditorController::class, 'baixarTemplate']);
});
