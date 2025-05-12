<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Services\ComponenteService;
use App\Traits\HandlesExceptions;

class Componentes extends Page
{
    use HandlesExceptions;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.componentes.index';

    public function getViewData(): array
    {
        try {
            $service = app(ComponenteService::class);
            $componentes = $service->listarTodos();

            return [
                'componentes' => $componentes,
            ];
        } catch (\Exception $e) {
            $this->handleException('Erro ao abrir gerenciador de componentes:', $e);
            return [
                'componentes' => [],
                'erro' => $e->getMessage(),
            ];
        }
    }
}
