<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrapesJSEditorController;

// Rotas do editor GrapesJS
Route::prefix('editor')->group(function () {
    Route::get('/template/{template}', [GrapesJSEditorController::class, 'index'])->name('editor.template'); // Exibe editor com template
    Route::post('/salvar-template', [GrapesJSEditorController::class, 'salvarTemplate']); // Salva template
    Route::get('/get-template/{template}', [GrapesJSEditorController::class, 'carregarUltimaVersao']); // Carrega última versão
    Route::post('/upload-imagem', [GrapesJSEditorController::class, 'uploadImagem']); // Faz upload de imagem
    Route::post('/baixar-template', [GrapesJSEditorController::class, 'baixarTemplate']); // Baixa template em ZIP
});
