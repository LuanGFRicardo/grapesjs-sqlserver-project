<?php

namespace App\Filament\Resources\TemplateHistoricoResource\Pages;

use App\Filament\Resources\TemplateHistoricoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTemplateHistorico extends EditRecord
{
    // Resource associado
    protected static string $resource = TemplateHistoricoResource::class;

    // Ação de excluir no cabeçalho
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
