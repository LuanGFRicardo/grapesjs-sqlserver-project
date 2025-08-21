<?php

namespace App\Filament\Resources\NoticiaResource\Pages;

use App\Filament\Resources\NoticiaResource;
use Filament\Resources\Pages\EditRecord;
use App\Services\TemplateService;
use Illuminate\Support\Facades\Log;
use App\Traits\HandlesExceptions;

class EditNoticia extends EditRecord
{
    use HandlesExceptions;

    protected static string $resource = NoticiaResource::class;

    protected ?string $nomeTemplateCriado = null;

    // Atualiza template após salvar a notícia
    protected function afterSave(): void
    {
        $noticia = $this->record;
        $templateService = app(TemplateService::class);

        try {
            if (!$noticia->template_id) {
                Log::warning('Notícia sem template_id para atualização: ID ' . $noticia->id);
                return;
            }

            $resultado = $templateService->atualizarNomeTemplate($noticia->template_id, $noticia->nome);

            if (!empty($resultado['success'])) {
                $this->nomeTemplateCriado = $resultado['nome'] ?? null;
            } else {
                Log::error('Falha ao atualizar template: ' . ($resultado['error'] ?? 'Erro desconhecido'));
                return;
            }

        } catch (\Throwable $e) {
            $this->handleException('Erro ao atualizar template após editar notícia:', $e);
            return;
        }
    }

    // Redireciona para editor do template atualizado
    protected function getRedirectUrl(): string
    {
        if ($this->nomeTemplateCriado) {
            $template = \App\Models\Template::where('nome', $this->nomeTemplateCriado)->first();

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
}
