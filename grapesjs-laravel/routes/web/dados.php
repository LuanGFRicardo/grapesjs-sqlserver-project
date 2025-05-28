<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultaDadosController;

Route::get('/dados/{tipo}', [ConsultaDadosController::class, 'obter']);
