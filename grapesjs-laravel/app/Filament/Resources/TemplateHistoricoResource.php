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
    // Define o model associado à resource
    protected static ?string $model = TemplateHistorico::class;

    // Configurações de navegação
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Histórico de Templates';
    protected static ?string $navigationGroup = 'GrapesJS';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Seleção do template relacionado
                Forms\Components\Select::make('template_id')
                    ->label('Template')
                    ->relationship('template', 'nome')
                    ->required(),

                // Campo para armazenar o HTML
                Forms\Components\Textarea::make('html')
                    ->label('HTML')
                    ->rows(10)
                    ->columnSpanFull(),

                // Campo para armazenar o CSS
                Forms\Components\Textarea::make('css')
                    ->label('CSS')
                    ->rows(5)
                    ->columnSpanFull(),

                // Campo para armazenar o projeto em JSON
                Forms\Components\Textarea::make('projeto')
                    ->label('Projeto (JSON)')
                    ->rows(10)
                    ->columnSpanFull(),

                // Data de criação registrada automaticamente
                Forms\Components\DateTimePicker::make('data_criacao')
                    ->label('Data de Criação')
                    ->default(now())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Exibe o nome do template relacionado
                Tables\Columns\TextColumn::make('template.nome')
                    ->label('Template')
                    ->sortable()
                    ->searchable()
                    ->limit(30),

                // Exibe a data de criação formatada
                Tables\Columns\TextColumn::make('data_criacao')
                    ->label('Data de Criação')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                // Ação customizada para editar no editor GrapesJS
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

    public static function getRelations(): array
    {
        return [
            // Sem relacionamentos adicionais
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTemplateHistoricos::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Mantém a query padrão sem alterações
        return parent::getEloquentQuery();
    }
}
