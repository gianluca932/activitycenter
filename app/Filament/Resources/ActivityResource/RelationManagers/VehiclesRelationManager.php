<?php

namespace App\Filament\Resources\ActivityResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class VehiclesRelationManager extends RelationManager
{
    protected static string $relationship = 'vehicles';

    protected static ?string $title = 'Mezzi impiegati';

    public function canCreate(): bool
    {
        return false; // niente "New vehicle" da qui
    }

    public function form(Form $form): Form
    {
        // se in futuro vuoi campi pivot per mezzi, li metti qui
        return $form->schema([
            // esempio:
            // Forms\Components\TextInput::make('notes')->label('Note'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('plate')
                    ->label('Targa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('model')
                    ->label('Modello')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('base.name')
                    ->label('Sede')
                    ->sortable()
                    ->toggleable(),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Aggiungi mezzo')
                    ->recordSelectSearchColumns(['plate', 'type', 'model'])
                    ->recordTitle(fn ($record) => "{$record->plate} - {$record->type} {$record->model}")
                    ->preloadRecordSelect()
                
            ])
            ->actions([
                Tables\Actions\DetachAction::make()->label('Rimuovi'),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()->label('Rimuovi selezionati'),
            ]);
    }
}
