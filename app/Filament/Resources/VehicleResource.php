<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $modelLabel = 'Mezzo';
    protected static ?string $pluralModelLabel = 'Mezzi';
    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('plate')
                    ->label('Targa')
                    ->required()
                    ->maxLength(20),

                Forms\Components\TextInput::make('brand')
                    ->label('Marca')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('model')
    ->label('Modello')
    ->required()
    ->maxLength(255),

                Forms\Components\DatePicker::make('revision_expires_at')
                    ->label('Scadenza Revisione'),

                Forms\Components\DatePicker::make('insurance_expires_at')
                    ->label('Scadenza Assicurazione'),

                // ✅ Sede (Base) come relazione
                Forms\Components\Select::make('base_id')
                    ->label('Sede')
                    ->relationship('base', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome sede')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->label('Codice')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('city')
                            ->label('Città')
                            ->maxLength(255),
                    ])
                    ->editOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome sede')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->label('Codice')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('city')
                            ->label('Città')
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('plate')
                    ->label('Targa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('brand')
                    ->label('Marca')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('model')
                    ->label('Modello')
                    ->searchable()
                    ->sortable(),
                    

                Tables\Columns\TextColumn::make('revision_expires_at')
                    ->label('Scadenza Revisione')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('insurance_expires_at')
                    ->label('Scadenza Assicurazione')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('base.name')
                    ->label('Sede')
                    ->searchable()
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
            ->filters([
                Tables\Filters\SelectFilter::make('base')
                    ->label('Sede')
                    ->relationship('base', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Modifica'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Elimina'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
