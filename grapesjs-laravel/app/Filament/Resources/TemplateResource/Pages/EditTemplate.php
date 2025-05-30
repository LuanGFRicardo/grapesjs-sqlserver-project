<?php

namespace App\Filament\Resources\TemplateResource\Pages;

use App\Filament\Resources\TemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Services\TemplateService;
use App\Traits\HandlesExceptions;

class EditTemplate extends EditRecord
{
    protected static string $resource = TemplateResource::class;

    protected function afterSave(): void
    {
        $template = $this->record;
        $templateService = app(TemplateService::class);

        try {
            // Normaliza e atualiza/cria template GrapesJS
            $nomeNormalizado = $this->normalizarENomearTemplate($template, $templateService);
            $templateService->atualizarOuCriarTemplate($nomeNormalizado, '');
        } catch (\Exception $e) {
            $this->handleException('Erro ao editar template:', $e);
        }
    }

    protected function getHeaderActions(): array
    {
        // Botão de deletar registro
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        // Redireciona para a listagem da resource
        return $this->getResource()::getUrl();
    }
}
