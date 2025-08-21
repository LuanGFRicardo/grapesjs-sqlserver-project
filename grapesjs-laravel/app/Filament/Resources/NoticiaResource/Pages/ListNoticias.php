<?php

namespace App\Filament\Resources\NoticiaResource\Pages;

use App\Filament\Resources\NoticiaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNoticias extends ListRecords
{
    protected static string $resource = NoticiaResource::class;

    // Ação para criar nova notícia no cabeçalho
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
