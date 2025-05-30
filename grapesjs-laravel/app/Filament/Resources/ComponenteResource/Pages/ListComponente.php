<?php

namespace App\Filament\Resources\ComponenteResource\Pages;

use App\Filament\Resources\ComponenteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComponente extends ListRecords
{
    protected static string $resource = ComponenteResource::class;

    // Botão para criar novo componente
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
