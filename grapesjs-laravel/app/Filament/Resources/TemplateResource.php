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
    // Model associado
    protected static ?string $model = Template::class;

    // Configurações de navegação
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Templates';
    protected static ?string $navigationGroup = 'GrapesJS';
    protected static ?int $navigationSort = 1;

    // Formulário de criação/edição
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->label('Nome do Template')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Informe o nome do template'),
            ]);
    }

    // Tabela de listagem
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

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->data_exclusao ? 'Excluído' : 'Ativo')
                    ->colors([
                        'success' => fn ($state) => $state === 'Ativo',
                        'danger' => fn ($state) => $state === 'Excluído',
                    ]),
            ])
            ->filters([
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
                Action::make('editarComGrapesjs')
                    ->label('Editar Template')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn ($record) => 
                        $record ? route('editor.template', ['template' => $record->nome]) : '#'
                    )
                    ->openUrlInNewTab()
                    ->color('success')
                    ->visible(fn ($record) => filled($record->nome)),

                Tables\Actions\EditAction::make(),

                Action::make('marcarComoExcluido')
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

    // Sem relações adicionais
    public static function getRelations(): array
    {
        return [];
    }

    // Rotas das páginas
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }

    // Query padrão
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
