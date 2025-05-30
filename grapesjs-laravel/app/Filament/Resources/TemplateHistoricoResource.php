<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TemplateHistoricoResource\Pages;
use App\Models\TemplateHistorico;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class TemplateHistoricoResource extends Resource
{
    // Model associado
    protected static ?string $model = TemplateHistorico::class;

    // Configurações de navegação
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Histórico';
    protected static ?string $navigationGroup = 'GrapesJS';
    protected static ?string $modelLabel = 'Histórico';
    protected static ?string $pluralModelLabel = 'Histórico';
    protected static ?int $navigationSort = 2;

    // Formulário de criação/edição
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('template_id')
                    ->label('Template')
                    ->relationship('template', 'nome')
                    ->required(),

                Forms\Components\Textarea::make('html')
                    ->label('HTML')
                    ->rows(10)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('css')
                    ->label('CSS')
                    ->rows(5)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('projeto')
                    ->label('Projeto (JSON)')
                    ->rows(10)
                    ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('data_criacao')
                    ->label('Data de Criação')
                    ->default(now())
                    ->required(),
            ]);
    }

    // Tabela de listagem
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('template.nome')
                    ->label('Template')
                    ->sortable()
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('data_criacao')
                    ->label('Data de Criação')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Action::make('editarComGrapesjs')
                    ->label('Editar com GrapesJS')
                    ->icon('heroicon-o-pencil-square')
                    ->color('success')
                    ->url(fn ($record) => 
                        $record && $record->template
                            ? route('editor.template', [
                                'template' => $record->template_nome,
                                '?versao=' . $record->id
                              ])
                            : '#'
                    )
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => filled($record->id)),
            ])
            ->bulkActions([])
            ->selectable(false)
            ->defaultSort('data_criacao', 'desc')
            ->paginationPageOptions([10, 25, 50, 100, 250]);
    }

    // Sem relacionamentos adicionais
    public static function getRelations(): array
    {
        return [];
    }

    // Rotas das páginas
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTemplateHistoricos::route('/'),
        ];
    }

    // Query padrão
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
