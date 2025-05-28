<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponenteController;

Route::prefix('componentes')->group(function () {
    Route::get('/', [ComponenteController::class, 'listarComponentes']);
});