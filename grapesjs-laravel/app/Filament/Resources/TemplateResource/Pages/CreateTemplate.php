<?php

namespace App\Filament\Resources\TemplateResource\Pages;

use App\Filament\Resources\TemplateResource;
use App\Services\MenuService;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\HandlesExceptions;

class CreateTemplate extends CreateRecord
{
    use HandlesExceptions;

    protected static string $resource = TemplateResource::class;

    protected function afterCreate(): void
    {
        $menuService = app(MenuService::class);

        try {
            $nome = $this->record->nome ?? 'Template sem nome';
            $menuService->criarTemplate($nome);
        } catch (\Exception $e) {
            $this->handleException('Erro ao criar Template Historico:', $e->getMessage());
        }
    }
}
