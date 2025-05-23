<?php

namespace App\Filament\Resources\TemplateHistoricoResource\Pages;

use App\Filament\Resources\TemplateHistoricoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTemplateHistorico extends EditRecord
{
    protected static string $resource = TemplateHistoricoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
