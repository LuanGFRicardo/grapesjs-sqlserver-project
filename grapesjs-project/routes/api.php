<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrapesEditorController;
use App\Http\Controllers\ConsultaDadosController;

Route::get('/dados/{tipo}', [ConsultaDadosController::class, 'obter']);
Route::post('/salvar-template', [GrapesEditorController::class, 'salvarTemplate']);
Route::get('/get-template/{title}', [GrapesEditorController::class, 'carregar']);
Route::post('/criar-template', [GrapesEditorController::class, 'criarTemplate']);
Route::get('/template-historico/{nome}', [GrapesEditorController::class, 'historicoTemplate']);
Route::get('/template-versao/{versaoId}', [GrapesEditorController::class, 'buscarVersao']);
