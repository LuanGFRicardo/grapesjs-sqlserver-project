<?php

namespace App\Filament\Resources\ComponenteResource\Pages;

use App\Filament\Resources\ComponenteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComponente extends EditRecord
{
    protected static string $resource = ComponenteResource::class;

    // Ação de deletar no cabeçalho
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // Redireciona para a lista após salvar
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
