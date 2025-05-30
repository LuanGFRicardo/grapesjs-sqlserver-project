<?php

namespace App\Filament\Resources\TemplateHistoricoResource\Pages;

use App\Filament\Resources\TemplateHistoricoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTemplateHistoricos extends ListRecords
{
    // Resource associado
    protected static string $resource = TemplateHistoricoResource::class;

    // Impede criação via interface
    protected function canCreate(): bool
    {
        return false;
    }
}
