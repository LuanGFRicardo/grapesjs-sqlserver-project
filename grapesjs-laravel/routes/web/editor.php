<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrapesEditorController;

// Rotas do editor GrapesJS
Route::prefix('editor')->group(function () {
    Route::get('/{template}', [GrapesEditorController::class, 'index'])->name('editor.template'); // Exibe editor com template
    Route::post('/salvar-template', [GrapesEditorController::class, 'salvarTemplate']); // Salva template
    Route::get('/get-template/{title}', [GrapesEditorController::class, 'carregarUltimaVersao']); // Carrega última versão
    Route::post('/upload-imagem', [GrapesEditorController::class, 'uploadImagem']); // Faz upload de imagem
    Route::post('/baixar-template', [GrapesEditorController::class, 'baixarTemplate']); // Baixa template em ZIP
});
