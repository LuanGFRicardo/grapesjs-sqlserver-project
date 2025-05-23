<?php

namespace App\Filament\Resources\TemplateHistoricoResource\Pages;

use App\Filament\Resources\TemplateHistoricoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTemplateHistoricos extends ListRecords
{
    protected static string $resource = TemplateHistoricoResource::class;

    protected function canCreate(): bool
    {
        return false;
    }
}
