<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VolunteerResource\Pages;
use App\Models\Volunteer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VolunteerResource extends Resource
{
    protected static ?string $model = Volunteer::class;

    protected static ?string $modelLabel = 'Volontario';
    protected static ?string $pluralModelLabel = 'Volontari';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('last_name')
                    ->label('Cognome')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('tax_code')
                    ->label('Codice Fiscale')
                    ->required()
                    ->maxLength(16),

                // ✅ Sede (Base) come relazione
                Forms\Components\Select::make('base_id')
                    ->label('Sede')
                    ->relationship('base', 'name')
                    ->searchable()
                    ->preload()
                    // permette di creare/modificare la sede direttamente dal form
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
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Nome')
                    ->searchable(),

                Tables\Columns\TextColumn::make('last_name')
                    ->label('Cognome')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tax_code')
                    ->label('Codice Fiscale')
                    ->searchable(),

                // ✅ mostra il nome della sede dalla relazione
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
                // (opzionale) filtro per sede
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
            'index' => Pages\ListVolunteers::route('/'),
            'create' => Pages\CreateVolunteer::route('/create'),
            'edit' => Pages\EditVolunteer::route('/{record}/edit'),
        ];
    }
}
