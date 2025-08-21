<?php

namespace App\Filament\Resources\TemplateResource\Pages;

use App\Filament\Resources\TemplateResource;
use App\Services\TemplateService;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\HandlesExceptions;
use App\Models\Template;

class CreateTemplate extends CreateRecord
{
    use HandlesExceptions;

    protected static string $resource = TemplateResource::class;

    protected ?string $nomeTemplateCriado = null;
    protected ?int $templateIdCriado = null;

    protected function afterCreate(): void
    {
        $template = $this->record;
        $templateService = app(TemplateService::class);

        try {
            $nomeOriginal = $template->nome;

            // Criação e captura do retorno
            $resultado = $templateService->criarTemplate($nomeOriginal, '');

            // Atribuição correta dos dados
            $this->nomeTemplateCriado = $resultado['nome'] ?? null;
            $this->templateIdCriado = $resultado['id'] ?? null;
        } catch (\Exception $e) {
            $this->handleException('Erro ao criar template:', $e);
        }
    }

    protected function getRedirectUrl(): string
    {
        if ($this->templateIdCriado) {
            $template = Template::find($this->templateIdCriado);
            if ($template) {
                $versao = $template->historicos()
                    ->orderByDesc('data_criacao')
                    ->first();

                if ($versao) {
                    return route('editor.template', [
                        'template' => $template->id,
                    ]) . '?versao=' . $versao->id;
                }
            }
        }

        // Redireciona para edição do template caso algo dê errado
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
