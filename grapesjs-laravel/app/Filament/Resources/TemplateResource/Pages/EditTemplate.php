<?php

namespace App\Filament\Resources\TemplateResource\Pages;

use App\Filament\Resources\TemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Services\TemplateService;
use App\Traits\HandlesExceptions;

class EditTemplate extends EditRecord
{
    use HandlesExceptions;

    protected static string $resource = TemplateResource::class;

    protected function afterSave(): void
    {
        $template = $this->record;
        $templateService = app(TemplateService::class);

        try {
            $templateService->atualizarNomeTemplate($template->id, $template->nome);
        } catch (\Exception $e) {
            $this->handleException('Erro ao editar template:', $e);
        }
    }

    protected function getRedirectUrl(): string
    {
        // Redireciona para a listagem da resource
        return $this->getResource()::getUrl();
    }
}
