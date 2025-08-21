<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoticiaResource\Pages;
use App\Models\Noticia;
use App\Models\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class NoticiaResource extends Resource
{
    protected static ?string $model = Noticia::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationLabel = 'Notícias';
    protected static ?string $modelLabel = 'Notícia';
    protected static ?string $pluralModelLabel = 'Notícias';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nome')
                ->label('Título')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('descricao')
                ->label('Descrição')
                ->required(),

            Forms\Components\RichEditor::make('texto')
                ->label('Conteúdo')
                ->columnSpanFull()
                ->visibleOn('create')
                ->dehydrated(false)
                ->afterStateHydrated(function ($state, callable $set) {
                    $set('texto_hidden', $state);
                })
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('texto_hidden', $state);
                }),

            Forms\Components\Hidden::make('texto_hidden')
                ->dehydrated(),

            Forms\Components\DateTimePicker::make('data')
                ->label('Data de Publicação')
                ->default(now())
                ->required(),

            Forms\Components\DateTimePicker::make('data_expira')
                ->label('Data de Expiração')
                ->nullable(),

            Forms\Components\TextInput::make('autor')
                ->label('Autor')
                ->required()
                ->maxLength(2000),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'publicado' => 'Publicado',
                    'rascunho' => 'Rascunho',
                    'arquivado' => 'Arquivado',
                ])
                ->default('publicado')
                ->required(),

            Forms\Components\FileUpload::make('imagem_gr')
                ->label('Imagem Grande')
                ->image()
                ->disk('public')
                ->nullable(),

            Forms\Components\FileUpload::make('imagem_pq')
                ->label('Imagem Pequena (Thumbnail)')
                ->image()
                ->disk('public')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('autor')
                    ->label('Autor')
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('data')
                    ->label('Data de Publicação')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($record) => filled($record->data_exclusao)
                        ? 'danger'
                        : match ($record->status) {
                            'publicado' => 'success',
                            'rascunho' => 'gray',
                            'arquivado' => 'warning',
                            default => 'gray',
                        }
                    )
                    ->formatStateUsing(fn ($state, $record) =>
                        filled($record->data_exclusao) ? 'Excluído' : ucfirst($state)
                    ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_custom')
                    ->label('Status')
                    ->options([
                        'publicado' => 'Publicado',
                        'rascunho' => 'Rascunho',
                        'arquivado' => 'Arquivado',
                        'excluido' => 'Excluído',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $status = $data['value'] ?? null;

                        if ($status === 'excluido') {
                            return $query->whereNotNull('data_exclusao');
                        }

                        if ($status) {
                            return $query->where('status', $status)
                                        ->whereNull('data_exclusao');
                        }

                        return $query;
                    }),

                Tables\Filters\Filter::make('data_publicacao')
                    ->form([
                        Forms\Components\DatePicker::make('publicado_desde')->label('Publicado desde'),
                        Forms\Components\DatePicker::make('publicado_ate')->label('Publicado até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['publicado_desde'], fn (Builder $q, $date) => $q->whereDate('data', '>=', $date))
                            ->when($data['publicado_ate'], fn (Builder $q, $date) => $q->whereDate('data', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('editarComGrapesjs')
                    ->label('Editar Template')
                    ->icon('heroicon-o-pencil-square')
                    ->url(function ($record) {
                        $template = $record->template;

                        if (!$template) {
                            return null;
                        }

                        $ultimaVersao = $template->historicos()
                            ->orderByDesc('data_criacao')
                            ->first();

                        return $ultimaVersao
                            ? route('editor.template', [
                                'template' => $template->id,
                                'versao' => $ultimaVersao->id
                            ])
                            : null;
                    })
                    ->openUrlInNewTab()
                    ->color('success')
                    ->visible(fn ($record) => filled(optional($record->template)->nome)),

                Action::make('marcarComoExcluido')
                    ->label('Excluir')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['data_exclusao' => now()]);

                        $template = $record->template;

                        if ($template && is_null($template->data_exclusao)) {
                            $template->update(['data_exclusao' => now()]);
                        }
                    })
                    ->visible(fn ($record) => $record && is_null($record->data_exclusao))
            ])
            ->bulkActions([])
            ->selectable(false)
            ->defaultSort('data', 'desc')
            ->paginationPageOptions([10, 25, 50, 100, 250]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNoticias::route('/'),
            'create' => Pages\CreateNoticia::route('/create'),
            'edit' => Pages\EditNoticia::route('/{record}/edit'),
        ];
    }
}
