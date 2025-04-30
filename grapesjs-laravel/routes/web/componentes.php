<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponenteController;

Route::get('/componentes', [ComponenteController::class, 'index'])->name('componentes.gerenciar');