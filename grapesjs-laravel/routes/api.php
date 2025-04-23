<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ConsultaDadosController;
use App\Http\Controllers\GrapesEditorController;
use App\Http\Controllers\ComponenteController;

// Criar um novo template com base no menu
Route::post('/criar-template', [MenuController::class, 'criarTemplate']);

// Obter o histórico de um template
Route::get('/template-historico/{nome}', [MenuController::class, 'historicoTemplate']);

// Buscar versão específica de um template
Route::get('/template-versao/{versaoId}', [MenuController::class, 'buscarVersao']);

// Obter dados de um tipo específico
Route::get('/dados/{tipo}', [ConsultaDadosController::class, 'obter']);

// Salva um template com base no editor GrapesJS
Route::post('/salvar-template', [GrapesEditorController::class, 'salvarTemplate']);

// Carregar um template específico
Route::get('/get-template/{title}', [GrapesEditorController::class, 'carregar']);

// Fazer upload de imagem
Route::post('/upload-imagem', [GrapesEditorController::class, 'uploadImagem']);

// Deletar imagem
// Route::post('/deletar-imagem', [GrapesEditorController::class, 'deletarImagem']);

// Buscar componente específico
Route::get('/componentes/{id}', [ComponenteController::class, 'buscarComponente']);

// Salvar um componente
Route::post('/componentes', [ComponenteController::class, 'salvarComponente']);

// Atualizar um componente
Route::put('/componentes/{id}', [ComponenteController::class, 'atualizarComponente']);

// Excluir um componente
Route::delete('/componentes/{id}', [ComponenteController::class, 'excluirComponente']);

// Listar componentes
Route::get('/listar-componentes', [ComponenteController::class, 'listarComponentes']);
