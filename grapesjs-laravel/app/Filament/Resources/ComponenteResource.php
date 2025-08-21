<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComponenteResource\Pages;
use App\Models\Componente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use App\Livewire\Forms\IconeSelect;

class ComponenteResource extends Resource
{
    // Modelo associado
    protected static ?string $model = Componente::class;

    // Configurações de navegação
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Componentes';
    protected static ?string $navigationGroup = 'GrapesJS';
    protected static ?string $modelLabel = 'Componentes';
    protected static ?string $pluralModelLabel = 'Componentes';
    protected static ?int $navigationSort = 3;

    // Formulário de criação/edição
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nome')
                ->label('Nome do Componente')
                ->required()
                ->maxLength(255)
                ->placeholder('Informe o nome do componente')
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('categoria')
                ->label('Categoria')
                ->required()
                ->maxLength(100)
                ->placeholder('Informe a categoria'),

            IconeSelect::make('icone')
                ->label('Ícone')
                ->columnSpanFull(),

            Forms\Components\Textarea::make('html')
                ->label('HTML')
                ->rows(10)
                ->columnSpanFull(),

            Forms\Components\Textarea::make('css')
                ->label('CSS')
                ->rows(5)
                ->columnSpanFull(),
        ]);
    }

    // Tabela de listagem e ações
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('categoria')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('data_criacao')
                    ->label('Data de Criação')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('data_modificacao')
                    ->label('Data de Modificação')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('data_exclusao')
                    ->label('Data de Exclusão')
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
                Tables\Actions\EditAction::make(),

                Action::make('marcarComoExcluido')
                    ->label('Excluir')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['data_exclusao' => now()]))
                    ->visible(fn ($record) => $record && is_null($record->data_exclusao)),
            ])
            ->bulkActions([]) // Sem ações em lote
            ->selectable(false) // Sem seleção múltipla
            ->defaultSort('data_criacao', 'desc') // Ordenação padrão
            ->paginationPageOptions([10, 25, 50, 100, 250]);
    }

    // Relações (nenhuma no momento)
    public static function getRelations(): array
    {
        return [];
    }

    // Rotas das páginas da Resource
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComponente::route('/'),
            'create' => Pages\CreateComponente::route('/create'),
            'edit' => Pages\EditComponente::route('/{record}/edit'),
        ];
    }
}
