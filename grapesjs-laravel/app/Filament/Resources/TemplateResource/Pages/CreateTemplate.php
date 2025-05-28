<?php

namespace App\Filament\Resources\TemplateResource\Pages;

use App\Filament\Resources\TemplateResource;
use App\Services\TemplateService;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\HandlesExceptions;

class CreateTemplate extends CreateRecord
{
    use HandlesExceptions;

    protected static string $resource = TemplateResource::class;

    protected ?string $nomeTemplateCriado = null;

    protected function afterCreate(): void
    {
        $template = $this->record;
        
        $templateService = app(TemplateService::class);

        try {
            $this->nomeTemplateCriado = $this->record->nome ?? 'Template sem nome';
            $templateService->atualizarOuCriarTemplate($this->nomeTemplateCriado, '');
        } catch (\Exception $e) {
            $this->handleException('Erro ao criar Template Historico:', $e->getMessage());
        }
    }

    protected function getRedirectUrl(): string
    {
        if ($this->nomeTemplateCriado)
        {
            return route('editor.template', ['template' => $this->nomeTemplateCriado]);
        }

        // Fallback padrão (editar template)
        return route('filament.admin.resources.templates.edit', [
            'record' => $this->record->getKey(),
        ]);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }
}
