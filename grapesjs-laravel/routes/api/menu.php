<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;

Route::post('/criar-template', [MenuController::class, 'criarTemplate']);
Route::get('/template-historico/{nome}', [MenuController::class, 'historicoTemplate']);
Route::get('/template-versao/{versaoId}', [MenuController::class, 'buscarVersao']);