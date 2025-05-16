<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

class LivewireServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap Livewire customization.
     */
    public function boot(): void
    {
        // Define rota customizada para update do Livewire
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/admin/livewire/update', $handle)->name('livewire.admin.update');
        });
    }
}
