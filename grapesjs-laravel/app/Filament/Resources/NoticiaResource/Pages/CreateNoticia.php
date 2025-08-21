<?php

namespace App\Filament\Resources\NoticiaResource\Pages;

use App\Filament\Resources\NoticiaResource;
use Filament\Resources\Pages\CreateRecord;
use App\Services\TemplateService;
use Illuminate\Support\Facades\Log;
use App\Models\Template;
use App\Traits\HandlesExceptions;

class CreateNoticia extends CreateRecord
{
    use HandlesExceptions;
    
    protected static string $resource = NoticiaResource::class;

    protected ?string $nomeTemplateCriado = null;
    protected ?int $templateIdCriado = null;

    // Cria notícia e associa template_id
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['data_cadastro'] = now();

        $texto = $data['texto_hidden'] ?? '';

        // Cria o template
        $templateService = app(TemplateService::class);

        try {
            $resultado = $templateService->criarTemplate($data['nome'], $texto);

            if (!empty($resultado['id'])) {
                // Vincula o template_id ao id do template
                $data['template_id'] = $resultado['id'];
                $this->nomeTemplateCriado = $resultado['nome'] ?? null;
                $this->templateIdCriado = $resultado['id'];
            }
        } catch (\Throwable $e) {
            $this->handleException('Erro ao criar template antes da notícia:', $e);
        }

        return $data;
    }

    // Redireciona para editor do template criado ou para edição da notícia
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

        return route('filament.admin.resources.noticias.edit', [
            'record' => $this->record->getKey(),
        ]);
    }

    // Define ações padrão do formulário: criar e cancelar
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }
}
