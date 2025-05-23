<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TemplateResource\Pages;
use App\Models\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class TemplateResource extends Resource
{
    // Model associado a esta Resource
    protected static ?string $model = Template::class;

    // Configurações de navegação no painel
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Templates';
    protected static ?string $navigationGroup = 'GrapesJS';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Campo obrigatório para nome do template
                Forms\Components\TextInput::make('nome')
                    ->label('Nome do Template')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Informe o nome do template'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('data_cadastro')
                    ->label('Data de Cadastro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('data_exclusao')
                    ->label('Data Exclusão')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Indicação visual de status com cores
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->data_exclusao ? 'Excluído' : 'Ativo')
                    ->colors([
                        'success' => fn ($state) => $state === 'Ativo',
                        'danger' => fn ($state) => $state === 'Excluído',
                    ]),
            ])
            ->filters([
                // Filtro de status baseado na data de exclusão
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'ativo' => 'Ativo',
                        'excluido' => 'Excluído',
                    ])
                    ->default('ativo')
                    ->query(function (Builder $query, array $data) {
                        if (($data['value'] ?? null) === 'excluido') {
                            return $query->whereNotNull('data_exclusao');
                        }
                        if (($data['value'] ?? null) === 'ativo') {
                            return $query->whereNull('data_exclusao');
                        }
                        return $query;
                    }),
            ])
            ->actions([
                // Ação para abrir no editor GrapesJS
                Action::make('editarComGrapesjs')
                    ->label('Editar Template')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn ($record) => $record ? route('editor.template', ['template' => $record->nome]) : '#')
                    ->openUrlInNewTab()
                    ->color('success')
                    ->visible(fn ($record) => filled($record->nome)),

                // Ação padrão de edição
                Tables\Actions\EditAction::make(),

                // Ação de soft delete com confirmação
                Tables\Actions\Action::make('marcarComoExcluido')
                    ->label('Excluir')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['data_exclusao' => now()]);
                    })
                    ->visible(fn ($record) => $record && is_null($record->data_exclusao)),
            ])
            ->bulkActions([])
            ->selectable(false)
            ->defaultSort('data_cadastro', 'desc')
            ->paginationPageOptions([10, 25, 50, 100, 250]);
    }

    public static function getRelations(): array
    {
        return [
            // Sem relações definidas no momento
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Mantém a query padrão; considerar ajuste para integração total com filtro de soft delete
        return parent::getEloquentQuery();
    }
}
