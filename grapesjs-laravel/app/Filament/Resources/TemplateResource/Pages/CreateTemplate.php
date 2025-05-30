<?php

namespace App\Filament\Resources\TemplateResource\Pages;

use App\Filament\Resources\TemplateResource;
use App\Services\TemplateService;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\HandlesExceptions;

class CreateTemplate extends CreateRecord
{
    protected static string $resource = TemplateResource::class;

    protected ?string $nomeTemplateCriado = null;

    protected function afterCreate(): void
    {
        $template = $this->record;
        $templateService = app(TemplateService::class);

        try {
            // Normaliza e nomeia o template
            $this->nomeTemplateCriado = $this->normalizarENomearTemplate($template, $templateService);

            // Atualiza ou cria estrutura GrapesJS
            $templateService->atualizarOuCriarTemplate($this->nomeTemplateCriado, '');
        } catch (\Exception $e) {
            $this->handleException('Erro ao criar template:', $e);
        }
    }

    protected function getRedirectUrl(): string
    {
        if ($this->nomeTemplateCriado) {
            return route('editor.template', ['template' => $this->nomeTemplateCriado]);
        }

        // Redireciona para edição do template
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
