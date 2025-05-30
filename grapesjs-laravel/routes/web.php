<?php

use Livewire\Livewire;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin'); // Redireciona para área admin
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/admin/livewire/update', $handle)->name('livewire.update'); // Define rota de update do Livewire
});

Route::middleware(['auth'])->group(function () {
    require __DIR__.'/web/editor.php'; // Inclui rotas do editor
    require __DIR__.'/web/componente.php'; // Inclui rotas de componentes
    require __DIR__.'/web/dados.php'; // Inclui rotas de dados
});
