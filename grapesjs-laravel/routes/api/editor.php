<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrapesEditorController;

Route::post('/salvar-template', [GrapesEditorController::class, 'salvarTemplate']);
Route::get('/get-template/{title}', [GrapesEditorController::class, 'carregar']);
Route::post('/upload-imagem', [GrapesEditorController::class, 'uploadImagem']);
Route::post('/baixar-template', [GrapesEditorController::class, 'baixarTemplate']);