<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Models\Activity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('category')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('requested_by')
                    ->maxLength(255),

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

                // ✅ Volunteers relation (max 10)
                Forms\Components\Select::make('volunteers')
                    ->label('Volontari')
                    ->relationship('volunteers', 'last_name')
                    ->getOptionLabelFromRecordUsing(
                        fn ($record) => "{$record->last_name} {$record->first_name} ({$record->tax_code})"
                    )
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->rules(['array', 'max:10'])
                    ->helperText('Massimo 10 volontari per attività.')
                    ->columnSpanFull(),

                // ✅ Vehicles relation (max 2)
                Forms\Components\Select::make('vehicles')
                    ->label('Mezzi')
                    ->relationship('vehicles', 'plate')
                    ->getOptionLabelFromRecordUsing(
                        fn ($record) => "{$record->plate} - {$record->brand}"
                    )
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->rules(['array', 'max:2'])
                    ->helperText('Massimo 2 mezzi per attività.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category')
                    ->searchable(),

                Tables\Columns\TextColumn::make('requested_by')
                    ->searchable(),

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

                // ✅ quick visibility
                Tables\Columns\TextColumn::make('volunteers_count')
                    ->counts('volunteers')
                    ->label('Volontari')
                    ->sortable(),

                Tables\Columns\TextColumn::make('vehicles_count')
                    ->counts('vehicles')
                    ->label('Mezzi')
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
                Tables\Actions\EditAction::make(),
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
            // Lasciamo vuoto: stiamo gestendo le relazioni direttamente nel form.
            // In alternativa si possono usare RelationManagers.
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
