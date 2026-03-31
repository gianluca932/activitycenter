<?php

namespace App\Filament\Resources;
use App\Filament\Resources\ActivityResource\RelationManagers;

use App\Filament\Resources\ActivityResource\Pages;
use App\Models\Activity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;



class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;
    protected static ?string $modelLabel = 'Attività';
    protected static ?string $pluralModelLabel = 'Attività';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('short_description')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('date_from')
                    ->required(),

                Forms\Components\DateTimePicker::make('date_to')
                    ->required()
                    ->after('date_from'),

                Forms\Components\TextInput::make('hours')
                    ->numeric()
                    ->helperText('Opzionale: puoi calcolarle a mano (per ora).'),


            Forms\Components\Select::make('activity_type_id')
    ->label('Tipo di Attività')
    ->relationship('activityType', 'name', fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('name'))
    ->required()
    ->searchable()
    ->preload()
    ->native(false)
    ->createOptionForm([
        Forms\Components\TextInput::make('name')->label('Nome')->required()->maxLength(255),
        Forms\Components\Toggle::make('is_active')->label('Attivo')->default(true),
        Forms\Components\TextInput::make('sort_order')->label('Ordine')->numeric()->default(0),
    ])
    ->editOptionForm([
        Forms\Components\TextInput::make('name')->label('Nome')->required()->maxLength(255),
        Forms\Components\Toggle::make('is_active')->label('Attivo'),
        Forms\Components\TextInput::make('sort_order')->label('Ordine')->numeric(),
    ]),

Forms\Components\Select::make('request_source_id')
    ->label('Richiesta di intervento pervenuta da')
    ->relationship('requestSource', 'name', fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('name'))
    ->required()
    ->searchable()
    ->preload()
    ->native(false)
    ->createOptionForm([
        Forms\Components\TextInput::make('name')->label('Nome')->required()->maxLength(255),
        Forms\Components\Toggle::make('is_active')->label('Attivo')->default(true),
        Forms\Components\TextInput::make('sort_order')->label('Ordine')->numeric()->default(0),
    ])
    ->editOptionForm([
        Forms\Components\TextInput::make('name')->label('Nome')->required()->maxLength(255),
        Forms\Components\Toggle::make('is_active')->label('Attivo'),
        Forms\Components\TextInput::make('sort_order')->label('Ordine')->numeric(),
    ]),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('short_description')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->short_description)
                    ->searchable(),

                Tables\Columns\TextColumn::make('date_from')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('date_to')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('hours')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
    Action::make('export_foglio_servizio')
        ->label('Esporta foglio di servizio')
        ->icon('heroicon-o-arrow-down-tray')
        ->url(fn (Activity $record) => route('pdf.foglio-servizio', $record))
        ->openUrlInNewTab(),

    Action::make('duplicate')
        ->label('Duplica')
        ->icon('heroicon-o-document-duplicate')
        ->action(function (Activity $record) {
            $duplicate = $record->replicate();
            $duplicate->save();

            // Duplica le relazioni
            $duplicate->volunteers()->attach($record->volunteers->pluck('id'));
            $duplicate->vehicles()->attach($record->vehicles->pluck('id'));

            Notification::make()
                ->title('Attività duplicata con successo')
                ->success()
                ->send();
        }),

    Tables\Actions\EditAction::make(),

    Tables\Actions\DeleteAction::make(),
])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

public static function getRelations(): array
{
    return [
        RelationManagers\VolunteersRelationManager::class,
        RelationManagers\VehiclesRelationManager::class,
    ];
}
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
}
