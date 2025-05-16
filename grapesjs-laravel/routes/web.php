<?php
use Livewire\Livewire;
use Illuminate\Support\Facades\Route;

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/admin/livewire/update', $handle)->name('livewire.update');
});

require __DIR__.'/web/menu.php';
require __DIR__.'/web/editor.php';
require __DIR__.'/web/componentes.php';
require __DIR__.'/web/template.php';