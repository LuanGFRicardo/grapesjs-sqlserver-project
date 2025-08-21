<?php

namespace App\Filament\Resources\ComponenteResource\Pages;

use App\Filament\Resources\ComponenteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComponente extends EditRecord
{
    protected static string $resource = ComponenteResource::class;

    // Redireciona para a listagem após salvar
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl();
    }

    // Ajusta dados do formulário antes de salvar
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['icone'] = $this->form->getState()['icone'];
        return $data;
    }
}
