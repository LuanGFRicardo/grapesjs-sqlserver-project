<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrapesEditorController;

Route::get('/template', [GrapesEditorController::class, 'template']);